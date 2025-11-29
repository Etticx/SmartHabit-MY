<?php
/**
 * Data Generation and Filtering Functions
 */

function generateMalaysiaHousingData() {
    // Load real housing data from processed Kaggle dataset
    $csv_file = __DIR__ . '/../data/processed_housing_data.csv';
    
    if (file_exists($csv_file)) {
        return loadRealHousingData($csv_file);
    } else {
        // Fallback to synthetic data if CSV not found
        return generateSyntheticData();
    }
}

/**
 * Load real housing data from CSV file
 */
function loadRealHousingData($csv_file) {
    $data = [];
    
    if (($handle = fopen($csv_file, 'r')) !== false) {
        // Read header row
        $headers = fgetcsv($handle);
        
        // Read data rows
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) === count($headers)) {
                $property = array_combine($headers, $row);
                
                $data[] = [
                    'Location' => $property['Location'],
                    'Latitude' => (float)$property['Latitude'],
                    'Longitude' => (float)$property['Longitude'],
                    'Distance_to_MRT' => (float)$property['Distance_to_MRT'],
                    'Amenity_Count' => (int)$property['Amenity_Count'],
                    'Safety_Index' => (float)$property['Safety_Index'],
                    'Property_Age' => (int)$property['Property_Age'],
                    'Square_Feet' => (int)$property['Square_Feet'],
                    'Rental_Price' => (int)$property['Rental_Price'],
                    'Property_Type' => $property['Property_Type'],
                    'Nearest_MRT' => $property['Nearest_MRT']
                ];
            }
        }
        fclose($handle);
    }
    
    return $data;
}

/**
 * Generate synthetic data (fallback)
 */
function generateSyntheticData() {
    // Real coordinates for Malaysian locations (Kuala Lumpur & Selangor)
    $locations = [
        'Cheras' => ['lat' => 3.1167, 'lng' => 101.7333],
        'Bangsar' => ['lat' => 3.1319, 'lng' => 101.6710],
        'Cyberjaya' => ['lat' => 2.9213, 'lng' => 101.6559],
        'Petaling Jaya' => ['lat' => 3.1073, 'lng' => 101.6067],
        'Mont Kiara' => ['lat' => 3.1725, 'lng' => 101.6508],
        'Subang Jaya' => ['lat' => 3.0437, 'lng' => 101.5810],
        'Shah Alam' => ['lat' => 3.0733, 'lng' => 101.5185],
        'Ampang' => ['lat' => 3.1478, 'lng' => 101.7620],
        'Damansara' => ['lat' => 3.1478, 'lng' => 101.6200],
        'Puchong' => ['lat' => 3.0330, 'lng' => 101.6069]
    ];
    
    $data = [];
    $n_samples = 200;
    
    // Set seed for consistent data
    mt_srand(42);
    
    for ($i = 0; $i < $n_samples; $i++) {
        $location_name = array_rand($locations);
        $coords = $locations[$location_name];
        
        // Add slight random offset for variety (within ~2km radius)
        $lat_offset = (mt_rand(-20, 20) / 1000);
        $lng_offset = (mt_rand(-20, 20) / 1000);
        
        $distance_to_mrt = round(mt_rand(5, 150) / 10, 2);
        $amenity_count = mt_rand(2, 20);
        $safety_index = round(mt_rand(40, 100) / 10, 1);
        
        // Calculate realistic rental price
        $base_price = 1000;
        $rental_price = $base_price + 
                       (15 - $distance_to_mrt) * 80 + 
                       $amenity_count * 50 + 
                       $safety_index * 100 + 
                       mt_rand(-200, 200);
        
        $rental_price = max(800, min(5000, round($rental_price)));
        
        $data[] = [
            'Location' => $location_name,
            'Latitude' => round($coords['lat'] + $lat_offset, 6),
            'Longitude' => round($coords['lng'] + $lng_offset, 6),
            'Distance_to_MRT' => $distance_to_mrt,
            'Amenity_Count' => $amenity_count,
            'Safety_Index' => $safety_index,
            'Rental_Price' => $rental_price
        ];
    }
    
    return $data;
}

function filterHousingData($data, $budget, $max_distance, $min_amenities, $location) {
    $filtered = array_filter($data, function($house) use ($budget, $max_distance, $min_amenities, $location) {
        $location_match = ($location === 'All' || $house['Location'] === $location);
        $budget_match = $house['Rental_Price'] <= $budget;
        $distance_match = $house['Distance_to_MRT'] <= $max_distance;
        $amenity_match = $house['Amenity_Count'] >= $min_amenities;
        
        return $location_match && $budget_match && $distance_match && $amenity_match;
    });
    
    // Sort by rental price (ascending)
    usort($filtered, function($a, $b) {
        return $a['Rental_Price'] <=> $b['Rental_Price'];
    });
    
    return array_values($filtered);
}
?>
