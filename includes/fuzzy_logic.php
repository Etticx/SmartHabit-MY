<?php
/**
 * Fuzzy Logic System for Housing Livability Assessment
 * Simplified PHP implementation of fuzzy inference
 */

function calculateLivabilityScore($distance, $price) {
    // Membership functions for Distance
    $distance_close = trimf($distance, 0, 0, 5);
    $distance_medium = trimf($distance, 3, 7, 11);
    $distance_far = trimf($distance, 9, 15, 15);
    
    // Membership functions for Price
    $price_affordable = trimf($price, 500, 500, 1800);
    $price_moderate = trimf($price, 1500, 2500, 3500);
    $price_expensive = trimf($price, 3000, 5500, 5500);
    
    // Fuzzy Rules (9 rules)
    $rules = [
        ['distance' => $distance_close, 'price' => $price_affordable, 'output' => 90],
        ['distance' => $distance_close, 'price' => $price_moderate, 'output' => 60],
        ['distance' => $distance_close, 'price' => $price_expensive, 'output' => 50],
        ['distance' => $distance_medium, 'price' => $price_affordable, 'output' => 80],
        ['distance' => $distance_medium, 'price' => $price_moderate, 'output' => 55],
        ['distance' => $distance_medium, 'price' => $price_expensive, 'output' => 30],
        ['distance' => $distance_far, 'price' => $price_affordable, 'output' => 50],
        ['distance' => $distance_far, 'price' => $price_moderate, 'output' => 25],
        ['distance' => $distance_far, 'price' => $price_expensive, 'output' => 15],
    ];
    
    // Calculate weighted average (simplified defuzzification)
    $numerator = 0;
    $denominator = 0;
    
    foreach ($rules as $rule) {
        $activation = min($rule['distance'], $rule['price']); // AND operation
        $numerator += $activation * $rule['output'];
        $denominator += $activation;
    }
    
    if ($denominator == 0) {
        return 50.0; // Default middle value
    }
    
    return round($numerator / $denominator, 1);
}

/**
 * Triangular membership function
 */
function trimf($x, $a, $b, $c) {
    if ($x <= $a || $x >= $c) {
        return 0;
    } elseif ($x == $b) {
        return 1;
    } elseif ($x > $a && $x < $b) {
        return ($x - $a) / ($b - $a);
    } else { // $x > $b && $x < $c
        return ($c - $x) / ($c - $b);
    }
}
?>
