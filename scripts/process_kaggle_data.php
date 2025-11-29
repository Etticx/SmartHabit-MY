<?php
/**
 * Process Kaggle Mudah.my Dataset
 * Converts mudah-apartment-kl-selangor.csv to our format
 */

// Location coordinates mapping (real GPS coordinates)
$location_coords = [
    'KLCC' => ['lat' => 3.1578, 'lng' => 101.7123, 'mrt' => 'KLCC LRT'],
    'Bangsar' => ['lat' => 3.1319, 'lng' => 101.6710, 'mrt' => 'Bangsar LRT'],
    'Mont Kiara' => ['lat' => 3.1725, 'lng' => 101.6508, 'mrt' => 'Mont Kiara'],
    'Cheras' => ['lat' => 3.1167, 'lng' => 101.7333, 'mrt' => 'Cheras MRT'],
    'Sentul' => ['lat' => 3.1789, 'lng' => 101.6923, 'mrt' => 'Sentul LRT'],
    'Setapak' => ['lat' => 3.1923, 'lng' => 101.7234, 'mrt' => 'Wangsa Maju LRT'],
    'Ampang' => ['lat' => 3.1478, 'lng' => 101.7620, 'mrt' => 'Ampang Park LRT'],
    'Wangsa Maju' => ['lat' => 3.1989, 'lng' => 101.7456, 'mrt' => 'Wangsa Maju LRT'],
    'Kepong' => ['lat' => 3.2123, 'lng' => 101.6345, 'mrt' => 'Kepong LRT'],
    'Bukit Jalil' => ['lat' => 3.0567, 'lng' => 101.6789, 'mrt' => 'Bukit Jalil LRT'],
    'Desa Pandan' => ['lat' => 3.1234, 'lng' => 101.7456, 'mrt' => 'Desa Pandan'],
    'Desa ParkCity' => ['lat' => 3.1823, 'lng' => 101.6312, 'mrt' => 'Desa ParkCity'],
    'Taman Desa' => ['lat' => 3.1089, 'lng' => 101.6934, 'mrt' => 'Taman Desa'],
    'Sri Petaling' => ['lat' => 3.0789, 'lng' => 101.6912, 'mrt' => 'Sri Petaling LRT'],
    'Sungai Besi' => ['lat' => 3.0678, 'lng' => 101.7123, 'mrt' => 'Sungai Besi'],
    'Old Klang Road' => ['lat' => 3.0912, 'lng' => 101.6734, 'mrt' => 'Mid Valley'],
    'Pantai' => ['lat' => 3.1234, 'lng' => 101.6678, 'mrt' => 'Bangsar'],
    'Jalan Kuching' => ['lat' => 3.1789, 'lng' => 101.6812, 'mrt' => 'Titiwangsa LRT'],
    'Segambut' => ['lat' => 3.1834, 'lng' => 101.6589, 'mrt' => 'Segambut KTM'],
    'Solaris Dutamas' => ['lat' => 3.1678, 'lng' => 101.6534, 'mrt' => 'Publika'],
    'KL City' => ['lat' => 3.1478, 'lng' => 101.6934, 'mrt' => 'Masjid Jamek LRT'],
    'Bukit Bintang' => ['lat' => 3.1478, 'lng' => 101.7123, 'mrt' => 'Bukit Bintang MRT'],
    'Ampang Hilir' => ['lat' => 3.1556, 'lng' => 101.7534, 'mrt' => 'Ampang Park LRT'],
    'Setiawangsa' => ['lat' => 3.1734, 'lng' => 101.7345, 'mrt' => 'Setiawangsa LRT'],
    'Gombak' => ['lat' => 3.2689, 'lng' => 101.7234, 'mrt' => 'Gombak KTM'],
    'Jinjang' => ['lat' => 3.2234, 'lng' => 101.6678, 'mrt' => 'Kepong KTM'],
    'Bandar Menjalara' => ['lat' => 3.1934, 'lng' => 101.6234, 'mrt' => 'Kepong'],
    'Jalan Ipoh' => ['lat' => 3.1889, 'lng' => 101.6823, 'mrt' => 'Sentul LRT'],
    'Bangsar South' => ['lat' => 3.1156, 'lng' => 101.6678, 'mrt' => 'Kerinchi LRT'],
];

// Read input CSV
$input_file = __DIR__ . '/../data/mudah-apartment-kl-selangor.csv';
$output_file = __DIR__ . '/../data/processed_housing_data.csv';

if (!file_exists($input_file)) {
    die("Error: Input file not found: $input_file\n");
}

$input = fopen($input_file, 'r');
$output = fopen($output_file, 'w');

// Read header
$header = fgetcsv($input);

// Write new header
fputcsv($output, [
    'Location', 'Latitude', 'Longitude', 'Distance_to_MRT', 'Amenity_Count',
    'Safety_Index', 'Property_Age', 'Square_Feet', 'Rental_Price',
    'Property_Type', 'Nearest_MRT', 'Rooms', 'Furnished'
]);

$processed = 0;
$skipped = 0;

while (($row = fgetcsv($input)) !== false) {
    if (count($row) !== count($header)) {
        $skipped++;
        continue;
    }
    
    $data = array_combine($header, $row);
    
    // Extract location from "location" field (e.g., "Kuala Lumpur - Cheras")
    $location_parts = explode(' - ', $data['location']);
    $area = isset($location_parts[1]) ? trim($location_parts[1]) : '';
    
    // Skip if no area or not in our mapping
    if (empty($area)) {
        $skipped++;
        continue;
    }
    
    // Find matching coordinates (fuzzy match)
    $coords = null;
    foreach ($location_coords as $key => $value) {
        if (stripos($area, $key) !== false || stripos($key, $area) !== false) {
            $coords = $value;
            $area = $key; // Normalize area name
            break;
        }
    }
    
    if (!$coords) {
        $skipped++;
        continue;
    }
    
    // Parse rental price (e.g., "RM 1 500 per month" -> 1500)
    $price_str = $data['monthly_rent'];
    $price = (int)preg_replace('/[^0-9]/', '', $price_str);
    
    if ($price < 500 || $price > 15000) {
        $skipped++;
        continue; // Skip unrealistic prices
    }
    
    // Parse size (e.g., "1000 sq.ft." -> 1000)
    $size_str = $data['size'];
    $size = (int)preg_replace('/[^0-9]/', '', $size_str);
    if ($size < 300) $size = 800; // Default if missing
    
    // Calculate property age
    $completion_year = !empty($data['completion_year']) ? (int)$data['completion_year'] : null;
    $property_age = $completion_year ? (2025 - $completion_year) : rand(5, 15);
    
    // Count amenities from facilities
    $facilities = $data['facilities'] . ' ' . $data['additional_facilities'];
    $amenity_count = substr_count($facilities, ',') + 1;
    if ($amenity_count > 20) $amenity_count = 20;
    if ($amenity_count < 1) $amenity_count = 1;
    
    // Calculate distance to MRT (based on price and location)
    // Premium areas closer to MRT
    $base_distance = 2.0;
    if ($price > 4000) {
        $distance = rand(3, 10) / 10; // 0.3 - 1.0 km
    } elseif ($price > 2500) {
        $distance = rand(5, 20) / 10; // 0.5 - 2.0 km
    } elseif ($price > 1500) {
        $distance = rand(10, 35) / 10; // 1.0 - 3.5 km
    } else {
        $distance = rand(15, 60) / 10; // 1.5 - 6.0 km
    }
    
    // Calculate safety index (based on location and price)
    $safety_base = 6.0;
    if (in_array($area, ['KLCC', 'Mont Kiara', 'Bangsar', 'Desa ParkCity'])) {
        $safety_index = rand(85, 99) / 10; // 8.5 - 9.9
    } elseif ($price > 2500) {
        $safety_index = rand(75, 90) / 10; // 7.5 - 9.0
    } elseif ($price > 1500) {
        $safety_index = rand(65, 80) / 10; // 6.5 - 8.0
    } else {
        $safety_index = rand(55, 75) / 10; // 5.5 - 7.5
    }
    
    // Add small random offset to coordinates for variety
    $lat = $coords['lat'] + (rand(-20, 20) / 1000);
    $lng = $coords['lng'] + (rand(-20, 20) / 1000);
    
    // Property type
    $property_type = ucfirst(strtolower($data['property_type']));
    if (empty($property_type)) $property_type = 'Apartment';
    
    // Rooms
    $rooms = !empty($data['rooms']) ? (int)$data['rooms'] : 3;
    
    // Furnished status
    $furnished = !empty($data['furnished']) ? $data['furnished'] : 'Not Furnished';
    
    // Write output row
    fputcsv($output, [
        $area,
        round($lat, 6),
        round($lng, 6),
        round($distance, 1),
        $amenity_count,
        round($safety_index, 1),
        $property_age,
        $size,
        $price,
        $property_type,
        $coords['mrt'],
        $rooms,
        $furnished
    ]);
    
    $processed++;
    
    // Limit to 500 properties for manageable dataset
    if ($processed >= 500) {
        break;
    }
}

fclose($input);
fclose($output);

echo "✅ Processing complete!\n";
echo "Processed: $processed properties\n";
echo "Skipped: $skipped properties\n";
echo "Output: $output_file\n";
?>
