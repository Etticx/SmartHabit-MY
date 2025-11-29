# 📊 Data Sources for SmartHabit-MY

This document outlines the data sources used and recommended for the SmartHabit-MY project, aligned with SDG XI Hackathon requirements.

---

## 🇲🇾 Malaysian Government Open Data Portals

### 1. **Malaysia Open Data Portal**
- **URL**: https://www.data.gov.my/
- **Relevant Datasets**:
  - Housing prices by state and district
  - Public transport (MRT/LRT) station locations
  - Crime statistics by area
  - Population density data
  - Urban planning zones

### 2. **Department of Statistics Malaysia (DOSM)**
- **URL**: https://www.dosm.gov.my/
- **Datasets**:
  - Household income and expenditure surveys
  - Housing affordability indices
  - Cost of living data
  - Demographic statistics

### 3. **Kuala Lumpur City Hall (DBKL) Open Data**
- **URL**: https://www.dbkl.gov.my/
- **Datasets**:
  - Property assessment data
  - Public facilities locations
  - Urban development plans
  - Green spaces and parks

### 4. **Selangor Open Data**
- **URL**: https://data.selangor.gov.my/
- **Datasets**:
  - Property transactions
  - Infrastructure development
  - Public amenities mapping

---

## 🌍 OpenStreetMap (OSM) Data

### **OpenStreetMap Malaysia**
- **URL**: https://www.openstreetmap.org/
- **Tools**: Overpass API, OSMnx Python library
- **Data Available**:
  - MRT/LRT station coordinates
  - Road networks and routing
  - Points of interest (schools, hospitals, malls)
  - Building footprints
  - Public transport routes

### **Usage in Project**:
```python
# Example: Extract MRT stations using OSMnx
import osmnx as ox

# Get MRT stations in Kuala Lumpur
tags = {'railway': 'station', 'network': 'Rapid KL'}
stations = ox.geometries_from_place('Kuala Lumpur, Malaysia', tags)
```

---

## 📈 Kaggle Datasets

### 1. **Malaysia Housing Dataset**
- **URL**: https://www.kaggle.com/datasets/dragonduck/property-prices-in-kuala-lumpur-malaysia
- **Contains**: Property prices, locations, sizes, amenities

### 2. **Global Housing Affordability**
- **URL**: https://www.kaggle.com/datasets/htagholdings/property-sales
- **Use**: Benchmark comparisons

### 3. **Urban Mobility Datasets**
- **URL**: https://www.kaggle.com/datasets/cityofLA/los-angeles-metro-ridership
- **Use**: Public transport usage patterns (adaptable to KL context)

---

## 🗺️ Geospatial Data Sources

### 1. **Google Maps API**
- **Services Used**:
  - Places API (amenities, POIs)
  - Distance Matrix API (travel times)
  - Geocoding API (address to coordinates)

### 2. **Mapbox**
- **URL**: https://www.mapbox.com/
- **Features**:
  - Custom map styling
  - Routing and navigation
  - Isochrone API (travel time zones)

### 3. **GeoJSON Malaysia Boundaries**
- **URL**: https://github.com/dosm-malaysia/data-open
- **Contains**: State, district, and mukim boundaries

---

## 🏢 Real Estate APIs (Future Integration)

### 1. **PropertyGuru API**
- **URL**: https://www.propertyguru.com.my/
- **Data**: Real-time property listings, prices, features

### 2. **iProperty Malaysia**
- **URL**: https://www.iproperty.com.my/
- **Data**: Rental and sale listings

### 3. **EdgeProp**
- **URL**: https://www.edgeprop.my/
- **Data**: Market trends, property news

---

## 🤖 AI/ML Model Training Data

### Current Implementation:
- **Synthetic Data**: 1000 samples generated based on realistic Malaysian housing patterns
- **Features**: Distance to MRT, amenities, safety index, property age, square footage
- **Target**: Rental prices (RM 800 - 5000)

### Planned Integration:
1. **Real Property Data**: Scrape/API from PropertyGuru, iProperty
2. **Government Statistics**: DOSM housing surveys
3. **OSM Geospatial**: Actual MRT coordinates, amenity counts
4. **Crime Data**: Police statistics for safety indices

---

## 📊 Data Processing Pipeline

```
1. Data Collection
   ├── Government portals (CSV/JSON)
   ├── OSM API (GeoJSON)
   └── Kaggle datasets (CSV)

2. Data Cleaning
   ├── Remove duplicates
   ├── Handle missing values
   └── Standardize formats

3. Feature Engineering
   ├── Calculate distances (Haversine formula)
   ├── Count nearby amenities (spatial joins)
   └── Normalize safety indices

4. Model Training
   ├── Random Forest (sklearn)
   ├── Neural Network (TensorFlow)
   └── Fuzzy Logic (custom implementation)

5. Deployment
   └── Export to PHP application
```

---

## 🔄 Data Update Strategy

### Real-time Updates:
- Property listings: Daily scraping
- MRT schedules: Weekly updates
- Crime statistics: Monthly from police data

### Static Data:
- District boundaries: Annual
- Infrastructure: Quarterly
- Census data: Every 5 years

---

## 📝 Data Attribution

All data sources are properly attributed in the application:
- Government data: © Department of Statistics Malaysia
- OSM data: © OpenStreetMap contributors
- Kaggle datasets: Individual dataset licenses

---

## 🚀 How to Access Data

### For Development:
```bash
# Install required libraries
pip install pandas geopandas osmnx requests

# Example: Download OSM data
python scripts/download_osm_data.py

# Example: Fetch government data
python scripts/fetch_gov_data.py
```

### For Production:
- Data stored in MySQL database
- Cached in Redis for fast access
- Updated via scheduled cron jobs

---

## 📧 Contact for Data Partnerships

For official data partnerships:
- **DOSM**: data@dosm.gov.my
- **DBKL**: info@dbkl.gov.my
- **PropertyGuru**: api@propertyguru.com

---

**Last Updated**: November 28, 2025  
**Maintained by**: SmartHabit-MY Team
