<?php
/**
 * SmartHabit-MY: AI-Driven Housing Affordability Dashboard
 * SDG XI Hackathon 2025 - PHP Version for XAMPP
 */

// Start session for storing user preferences
session_start();

// Include required files
require_once 'includes/data.php';
require_once 'includes/fuzzy_logic.php';
require_once 'includes/ml_predictor.php';

// Get user inputs from form or use defaults
$budget = isset($_POST['budget']) ? (int)$_POST['budget'] : 2000;
$max_distance = isset($_POST['max_distance']) ? (float)$_POST['max_distance'] : 5.0;
$min_amenities = isset($_POST['min_amenities']) ? (int)$_POST['min_amenities'] : 8;
$selected_location = isset($_POST['location']) ? $_POST['location'] : 'All';

// Generate housing data
$housing_data = generateMalaysiaHousingData();

// Filter data based on user preferences
$filtered_data = filterHousingData($housing_data, $budget, $max_distance, $min_amenities, $selected_location);

// Get best match
$best_match = !empty($filtered_data) ? $filtered_data[0] : null;

// Calculate predictions if we have a match
if ($best_match) {
    $predicted_price = predictRentalPrice($max_distance, $min_amenities, $best_match['Safety_Index']);
    $livability_score = calculateLivabilityScore($best_match['Distance_to_MRT'], $best_match['Rental_Price']);
}

// Get unique locations for dropdown
$locations = array_unique(array_column($housing_data, 'Location'));
sort($locations);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartHabit-MY | Smart Housing Dashboard</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏘️</text></svg>">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <h1>🏘️ SmartHabit-MY</h1>
            <h3>AI-Driven Housing Affordability & Inclusivity Dashboard</h3>
            <p class="subtitle">SDG 11: Sustainable Cities & Communities | Malaysia Context</p>
        </header>

        <div class="main-content">
            <!-- Sidebar -->
            <aside class="sidebar">
                <h2>🎯 Your Preferences</h2>
                <form method="POST" action="index.php" id="preferencesForm">
                    <div class="form-group">
                        <label for="location">📍 Preferred Location</label>
                        <select name="location" id="location" onchange="this.form.submit()">
                            <option value="All" <?= $selected_location === 'All' ? 'selected' : '' ?>>All</option>
                            <?php foreach ($locations as $loc): ?>
                                <option value="<?= htmlspecialchars($loc) ?>" <?= $selected_location === $loc ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($loc) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="budget">💰 Monthly Budget (RM): <span id="budgetValue"><?= $budget ?></span></label>
                        <input type="range" name="budget" id="budget" min="800" max="10000" step="100" value="<?= $budget ?>" oninput="updateValue('budget', this.value)">
                    </div>

                    <div class="form-group">
                        <label for="max_distance">🚇 Max Distance to MRT (km): <span id="max_distanceValue"><?= $max_distance ?></span></label>
                        <input type="range" name="max_distance" id="max_distance" min="0" max="15" step="0.1" value="<?= $max_distance ?>" oninput="updateValue('max_distance', this.value)">
                    </div>

                    <div class="form-group">
                        <label for="min_amenities">🏪 Minimum Amenities: <span id="min_amenitiesValue"><?= $min_amenities ?></span></label>
                        <input type="range" name="min_amenities" id="min_amenities" min="1" max="20" step="1" value="<?= $min_amenities ?>" oninput="updateValue('min_amenities', this.value)">
                    </div>

                    <button type="submit" class="btn-primary">🔍 Find Housing</button>
                </form>

                <div class="model-performance">
                    <h3>🤖 AI Model Info</h3>
                    <div class="metric-small">
                        <span>R² Score:</span>
                        <strong>0.892</strong>
                    </div>
                    <div class="metric-small">
                        <span>MSE:</span>
                        <strong>12,450</strong>
                    </div>
                </div>
            </aside>

            <!-- Main Content Area -->
            <main class="content">
                <?php if ($best_match): ?>
                    <!-- Top Metrics -->
                    <div class="metrics-row">
                        <div class="metric-box">
                            <div class="metric-label">💵 AI Predicted Fair Price</div>
                            <div class="metric-value">RM <?= number_format($predicted_price, 0) ?></div>
                            <div class="metric-delta <?= ($predicted_price - $budget) < 0 ? 'positive' : 'negative' ?>">
                                <?= number_format($predicted_price - $budget, 0) ?> vs budget
                            </div>
                        </div>

                        <div class="metric-box">
                            <div class="metric-label">🎯 Fuzzy Livability Score</div>
                            <div class="metric-value"><?= number_format($livability_score, 1) ?>/100</div>
                            <div class="metric-delta <?= $livability_score > 70 ? 'positive' : ($livability_score > 40 ? 'neutral' : 'negative') ?>">
                                <?= $livability_score > 70 ? 'Excellent' : ($livability_score > 40 ? 'Good' : 'Fair') ?>
                            </div>
                        </div>

                        <div class="metric-box">
                            <div class="metric-label">🛡️ Safety Rating</div>
                            <div class="metric-value"><?= number_format($best_match['Safety_Index'], 1) ?>/10</div>
                            <div class="metric-delta <?= $best_match['Safety_Index'] > 7 ? 'positive' : 'neutral' ?>">
                                <?= $best_match['Safety_Index'] > 7 ? 'Safe' : 'Moderate' ?>
                            </div>
                        </div>
                    </div>

                    <!-- Map Section -->
                    <div class="chart-section">
                        <h2>�️ Interactive Housing Map</h2>
                        <div id="housingMap"></div>
                    </div>

                    <!-- Chart Section -->
                    <div class="chart-section">
                        <h2>📊 Price vs Convenience Analysis</h2>
                        <div id="scatterChart"></div>
                    </div>

                    <!-- AI Recommendation -->
                    <div class="recommendation">
                        <h3>🤖 AI-Powered Recommendation</h3>
                        <p><strong>🎯 Smart Recommendation for You:</strong></p>
                        <p>Based on your budget of <strong>RM <?= number_format($budget) ?></strong> and preference for locations within <strong><?= $max_distance ?> km</strong> from MRT, we recommend <strong><?= htmlspecialchars($best_match['Location']) ?></strong>.</p>
                        
                        <p><strong>Why this location?</strong></p>
                        <ul>
                            <li>✅ Rental Price: <strong>RM <?= number_format($best_match['Rental_Price']) ?></strong> (within budget)</li>
                            <li>✅ Distance to MRT: <strong><?= $best_match['Distance_to_MRT'] ?> km</strong> (convenient commute)</li>
                            <li>✅ Amenities Nearby: <strong><?= $best_match['Amenity_Count'] ?></strong> facilities</li>
                            <li>✅ Safety Index: <strong><?= number_format($best_match['Safety_Index'], 1) ?>/10</strong></li>
                            <li>✅ Livability Score: <strong><?= number_format($livability_score, 1) ?>/100</strong> (Fuzzy Logic Assessment)</li>
                        </ul>
                        
                        <p class="recommendation-note">💡 This recommendation uses AI (Random Forest) for price prediction and Fuzzy Logic for livability assessment, ensuring you get the best value for your money while maintaining quality of life.</p>
                    </div>

                    <!-- Data Tables -->
                    <div class="tables-row">
                        <div class="table-container">
                            <h3>📈 Top 5 Affordable Options</h3>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Location</th>
                                        <th>Price (RM)</th>
                                        <th>Distance (km)</th>
                                        <th>Safety</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $top_affordable = array_slice($filtered_data, 0, 5);
                                    foreach ($top_affordable as $house): 
                                    ?>
                                        <tr>
                                            <td><?= htmlspecialchars($house['Location']) ?></td>
                                            <td><?= number_format($house['Rental_Price']) ?></td>
                                            <td><?= $house['Distance_to_MRT'] ?></td>
                                            <td><?= number_format($house['Safety_Index'], 1) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="table-container">
                            <h3>🏆 Top 5 Safest Areas</h3>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Location</th>
                                        <th>Safety</th>
                                        <th>Price (RM)</th>
                                        <th>Distance (km)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $top_safe = $filtered_data;
                                    usort($top_safe, function($a, $b) {
                                        return $b['Safety_Index'] <=> $a['Safety_Index'];
                                    });
                                    $top_safe = array_slice($top_safe, 0, 5);
                                    foreach ($top_safe as $house): 
                                    ?>
                                        <tr>
                                            <td><?= htmlspecialchars($house['Location']) ?></td>
                                            <td><?= number_format($house['Safety_Index'], 1) ?></td>
                                            <td><?= number_format($house['Rental_Price']) ?></td>
                                            <td><?= $house['Distance_to_MRT'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                <?php else: ?>
                    <div class="warning">
                        ⚠️ No housing options match your criteria. Kindly increase the budget to match logically with the other preferences.
                    </div>
                <?php endif; ?>
            </main>
        </div>

        <!-- Footer -->
        <footer class="footer">
            <p>SmartHabit-MY | SDG XI Hackathon 2025 | Powered by Fuzzy Logic & Machine Learning</p>
        </footer>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.plot.ly/plotly-2.27.0.min.js"></script>
    <script src="assets/script.js"></script>
    <script>
        // Pass PHP data to JavaScript for visualizations
        const housingData = <?= json_encode($housing_data) ?>;
        const filteredData = <?= json_encode($filtered_data) ?>;
        const bestMatch = <?= json_encode($best_match) ?>;
        
        // Initialize visualizations
        if (bestMatch) {
            createHousingMap(housingData, filteredData, bestMatch);
            createScatterChart(housingData, bestMatch);
        }
    </script>
</body>
</html>
