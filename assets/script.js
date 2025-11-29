// SmartHabit-MY JavaScript Functions

// Update slider value display
function updateValue(id, value) {
    document.getElementById(id + 'Value').textContent = value;
}

// Create interactive housing map using Leaflet.js
function createHousingMap(allData, filteredData, bestMatch) {
    // Initialize map centered on Kuala Lumpur
    const map = L.map('housingMap').setView([3.1390, 101.6869], 11);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 18
    }).addTo(map);
    
    // Custom marker icons
    const defaultIcon = L.divIcon({
        className: 'custom-marker',
        html: '<div style="background-color: #00d4ff; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white;"></div>',
        iconSize: [12, 12]
    });
    
    const filteredIcon = L.divIcon({
        className: 'custom-marker',
        html: '<div style="background-color: #ffaa00; width: 14px; height: 14px; border-radius: 50%; border: 2px solid white;"></div>',
        iconSize: [14, 14]
    });
    
    const bestMatchIcon = L.divIcon({
        className: 'custom-marker',
        html: '<div style="background-color: #00ff88; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 10px #00ff88;"></div>',
        iconSize: [20, 20]
    });
    
    // Add all housing markers (gray)
    allData.forEach(house => {
        const isFiltered = filteredData.some(f => 
            f.Latitude === house.Latitude && f.Longitude === house.Longitude
        );
        
        const isBest = bestMatch && 
            house.Latitude === bestMatch.Latitude && 
            house.Longitude === bestMatch.Longitude;
        
        if (isBest) return; // Skip best match, we'll add it separately
        
        const marker = L.marker([house.Latitude, house.Longitude], {
            icon: isFiltered ? filteredIcon : defaultIcon
        }).addTo(map);
        
        const popupContent = `
            <div style="color: #000; min-width: 200px;">
                <h3 style="margin: 0 0 10px 0; color: #0f3460;">${house.Location}</h3>
                <p style="margin: 5px 0;"><strong>Price:</strong> RM ${house.Rental_Price.toLocaleString()}/month</p>
                <p style="margin: 5px 0;"><strong>Distance to MRT:</strong> ${house.Distance_to_MRT} km</p>
                <p style="margin: 5px 0;"><strong>Amenities:</strong> ${house.Amenity_Count}</p>
                <p style="margin: 5px 0;"><strong>Safety:</strong> ${house.Safety_Index}/10</p>
            </div>
        `;
        
        marker.bindPopup(popupContent);
    });
    
    // Add best match marker (highlighted)
    if (bestMatch) {
        const bestMarker = L.marker([bestMatch.Latitude, bestMatch.Longitude], {
            icon: bestMatchIcon
        }).addTo(map);
        
        const bestPopupContent = `
            <div style="color: #000; min-width: 220px;">
                <h3 style="margin: 0 0 10px 0; color: #00ff88;">⭐ BEST MATCH</h3>
                <h4 style="margin: 0 0 10px 0; color: #0f3460;">${bestMatch.Location}</h4>
                <p style="margin: 5px 0;"><strong>Price:</strong> RM ${bestMatch.Rental_Price.toLocaleString()}/month</p>
                <p style="margin: 5px 0;"><strong>Distance to MRT:</strong> ${bestMatch.Distance_to_MRT} km</p>
                <p style="margin: 5px 0;"><strong>Amenities:</strong> ${bestMatch.Amenity_Count}</p>
                <p style="margin: 5px 0;"><strong>Safety:</strong> ${bestMatch.Safety_Index}/10</p>
            </div>
        `;
        
        bestMarker.bindPopup(bestPopupContent).openPopup();
        
        // Center map on best match
        map.setView([bestMatch.Latitude, bestMatch.Longitude], 13);
    }
    
    // Add legend
    const legend = L.control({position: 'bottomright'});
    legend.onAdd = function(map) {
        const div = L.DomUtil.create('div', 'map-legend');
        div.innerHTML = `
            <div style="background: rgba(26, 26, 46, 0.9); padding: 15px; border-radius: 8px; color: white;">
                <h4 style="margin: 0 0 10px 0; color: #00d4ff;">Legend</h4>
                <div style="margin: 5px 0;">
                    <span style="display: inline-block; width: 12px; height: 12px; background: #00d4ff; border-radius: 50%; border: 2px solid white; margin-right: 8px;"></span>
                    All Options
                </div>
                <div style="margin: 5px 0;">
                    <span style="display: inline-block; width: 14px; height: 14px; background: #ffaa00; border-radius: 50%; border: 2px solid white; margin-right: 8px;"></span>
                    Matches Criteria
                </div>
                <div style="margin: 5px 0;">
                    <span style="display: inline-block; width: 20px; height: 20px; background: #00ff88; border-radius: 50%; border: 3px solid white; margin-right: 8px;"></span>
                    Best Match
                </div>
            </div>
        `;
        return div;
    };
    legend.addTo(map);
}

// Create scatter chart using Plotly
function createScatterChart(data, bestMatch) {
    const trace1 = {
        x: data.map(d => d.Distance_to_MRT),
        y: data.map(d => d.Rental_Price),
        mode: 'markers',
        type: 'scatter',
        name: 'All Options',
        marker: {
            size: data.map(d => d.Amenity_Count),
            color: data.map(d => d.Safety_Index),
            colorscale: 'Viridis',
            showscale: true,
            colorbar: {
                title: 'Safety Score',
                titleside: 'right'
            },
            line: {
                color: 'white',
                width: 0.5
            }
        },
        text: data.map(d => d.Location),
        hovertemplate: '<b>%{text}</b><br>' +
                      'Distance: %{x} km<br>' +
                      'Price: RM %{y}<br>' +
                      '<extra></extra>'
    };

    const trace2 = {
        x: [bestMatch.Distance_to_MRT],
        y: [bestMatch.Rental_Price],
        mode: 'markers',
        type: 'scatter',
        name: 'Best Match',
        marker: {
            size: 20,
            color: '#00ff88',
            symbol: 'star',
            line: {
                color: 'white',
                width: 2
            }
        },
        text: [bestMatch.Location],
        hovertemplate: '<b>Best Match</b><br>' +
                      'Location: ' + bestMatch.Location + '<br>' +
                      'Price: RM ' + bestMatch.Rental_Price + '<br>' +
                      '<extra></extra>'
    };

    const layout = {
        title: 'Housing Options: Price vs Distance to MRT',
        xaxis: {
            title: 'Distance to MRT (km)',
            gridcolor: '#2d5a7b',
            color: '#ffffff'
        },
        yaxis: {
            title: 'Monthly Rent (RM)',
            gridcolor: '#2d5a7b',
            color: '#ffffff'
        },
        plot_bgcolor: 'rgba(0,0,0,0)',
        paper_bgcolor: 'rgba(0,0,0,0)',
        font: {
            color: '#ffffff'
        },
        showlegend: true,
        legend: {
            x: 1,
            xanchor: 'right',
            y: 1
        },
        hovermode: 'closest'
    };

    const config = {
        responsive: true,
        displayModeBar: false
    };

    Plotly.newPlot('scatterChart', [trace1, trace2], layout, config);
}

// Auto-submit form on slider change (with debounce)
let submitTimeout;
document.querySelectorAll('input[type="range"]').forEach(slider => {
    slider.addEventListener('change', function() {
        clearTimeout(submitTimeout);
        submitTimeout = setTimeout(() => {
            document.getElementById('preferencesForm').submit();
        }, 500);
    });
});
