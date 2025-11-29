<?php
/**
 * Machine Learning Price Predictor
 * Simplified linear regression model for rental price prediction
 */

function predictRentalPrice($distance, $amenities, $safety) {
    // Coefficients derived from training (simplified model)
    $intercept = 1000;
    $coef_distance = -80;
    $coef_amenities = 50;
    $coef_safety = 100;
    
    $predicted_price = $intercept + 
                      ($coef_distance * $distance) + 
                      ($coef_amenities * $amenities) + 
                      ($coef_safety * $safety);
    
    // Clip to realistic range
    return max(800, min(5000, round($predicted_price)));
}
?>
