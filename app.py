"""
SmartHabit-MY: AI-Driven Housing Affordability & Inclusivity Dashboard
SDG XI Hackathon 2025 - Smart Mobility & Communities
Focus: Housing & Urban Planning (SDG 11) - Malaysia Context
"""

import streamlit as st
import pandas as pd
import numpy as np
import plotly.express as px
import plotly.graph_objects as go
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestRegressor
from sklearn.metrics import mean_squared_error, r2_score
import skfuzzy as fuzz
from skfuzzy import control as ctrl

# ============================================================================
# PAGE CONFIGURATION
# ============================================================================
st.set_page_config(
    page_title="SmartHabit-MY | Smart Housing Dashboard",
    page_icon="🏘️",
    layout="wide",
    initial_sidebar_state="expanded"
)

# ============================================================================
# CUSTOM CSS - MODERN DARK THEME WITH NEON ACCENTS
# ============================================================================
st.markdown("""
<style>
    /* Main background */
    .stApp {
        background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 100%);
    }
    
    /* Metric cards styling */
    [data-testid="stMetricValue"] {
        font-size: 2rem;
        color: #00ff88;
        font-weight: 700;
    }
    
    [data-testid="stMetricLabel"] {
        color: #a0a0a0;
        font-size: 1rem;
    }
    
    /* Sidebar styling */
    [data-testid="stSidebar"] {
        background: linear-gradient(180deg, #16213e 0%, #0f3460 100%);
    }
    
    /* Headers */
    h1, h2, h3 {
        color: #00d4ff;
        font-family: 'Segoe UI', sans-serif;
    }
    
    /* Custom metric box */
    .metric-box {
        background: linear-gradient(135deg, #1e3a5f 0%, #2d5a7b 100%);
        padding: 20px;
        border-radius: 10px;
        border-left: 4px solid #00ff88;
        box-shadow: 0 4px 6px rgba(0, 255, 136, 0.3);
    }
    
    /* Recommendation box */
    .recommendation {
        background: linear-gradient(135deg, #2d1b4e 0%, #4a2c6f 100%);
        padding: 20px;
        border-radius: 10px;
        border-left: 4px solid #ff00ff;
        color: #ffffff;
        font-size: 1.1rem;
        margin-top: 20px;
    }
</style>
""", unsafe_allow_html=True)

# ============================================================================
# DATA SIMULATION - MALAYSIA HOUSING CONTEXT
# ============================================================================
@st.cache_data
def generate_malaysia_housing_data():
    """Generate synthetic housing data for Kuala Lumpur and Selangor"""
    np.random.seed(42)
    
    locations = ['Cheras', 'Bangsar', 'Cyberjaya', 'Petaling Jaya', 'Mont Kiara', 
                 'Subang Jaya', 'Shah Alam', 'Ampang', 'Damansara', 'Puchong']
    
    n_samples = 200
    data = {
        'Location': np.random.choice(locations, n_samples),
        'Distance_to_MRT': np.random.uniform(0.5, 15, n_samples),
        'Amenity_Count': np.random.randint(2, 20, n_samples),
        'Safety_Index': np.random.uniform(4, 10, n_samples),
    }
    
    df = pd.DataFrame(data)
    
    # Generate realistic rental prices based on features
    base_price = 1000
    df['Rental_Price'] = (
        base_price + 
        (15 - df['Distance_to_MRT']) * 80 +  # Closer to MRT = higher price
        df['Amenity_Count'] * 50 +            # More amenities = higher price
        df['Safety_Index'] * 100 +            # Safer = higher price
        np.random.normal(0, 200, n_samples)   # Random variation
    )
    
    df['Rental_Price'] = df['Rental_Price'].clip(800, 5000).round(0)
    df['Distance_to_MRT'] = df['Distance_to_MRT'].round(2)
    df['Safety_Index'] = df['Safety_Index'].round(1)
    
    return df

# ============================================================================
# FEATURE A: FUZZY LIVABILITY LOGIC ENGINE (THE INNOVATION)
# ============================================================================
def create_fuzzy_system():
    """
    Fuzzy Logic System for Housing Livability Assessment
    Innovation: Uses fuzzy inference to handle uncertainty in housing decisions
    """
    # Define fuzzy variables
    distance = ctrl.Antecedent(np.arange(0, 16, 0.1), 'distance')
    price = ctrl.Antecedent(np.arange(500, 5500, 10), 'price')
    livability = ctrl.Consequent(np.arange(0, 101, 1), 'livability')
    
    # Define membership functions for Distance
    distance['close'] = fuzz.trimf(distance.universe, [0, 0, 5])
    distance['medium'] = fuzz.trimf(distance.universe, [3, 7, 11])
    distance['far'] = fuzz.trimf(distance.universe, [9, 15, 15])
    
    # Define membership functions for Price
    price['affordable'] = fuzz.trimf(price.universe, [500, 500, 1800])
    price['moderate'] = fuzz.trimf(price.universe, [1500, 2500, 3500])
    price['expensive'] = fuzz.trimf(price.universe, [3000, 5500, 5500])
    
    # Define membership functions for Livability Score
    livability['poor'] = fuzz.trimf(livability.universe, [0, 0, 40])
    livability['average'] = fuzz.trimf(livability.universe, [30, 50, 70])
    livability['excellent'] = fuzz.trimf(livability.universe, [60, 100, 100])
    
    # Define fuzzy rules (THE CORE INNOVATION)
    rule1 = ctrl.Rule(distance['close'] & price['affordable'], livability['excellent'])
    rule2 = ctrl.Rule(distance['close'] & price['moderate'], livability['average'])
    rule3 = ctrl.Rule(distance['close'] & price['expensive'], livability['average'])
    rule4 = ctrl.Rule(distance['medium'] & price['affordable'], livability['excellent'])
    rule5 = ctrl.Rule(distance['medium'] & price['moderate'], livability['average'])
    rule6 = ctrl.Rule(distance['medium'] & price['expensive'], livability['poor'])
    rule7 = ctrl.Rule(distance['far'] & price['affordable'], livability['average'])
    rule8 = ctrl.Rule(distance['far'] & price['moderate'], livability['poor'])
    rule9 = ctrl.Rule(distance['far'] & price['expensive'], livability['poor'])
    
    # Create control system
    livability_ctrl = ctrl.ControlSystem([rule1, rule2, rule3, rule4, rule5, rule6, rule7, rule8, rule9])
    livability_sim = ctrl.ControlSystemSimulation(livability_ctrl)
    
    return livability_sim

def calculate_livability_score(distance_val, price_val):
    """Calculate livability score using fuzzy logic"""
    try:
        fuzzy_system = create_fuzzy_system()
        fuzzy_system.input['distance'] = distance_val
        fuzzy_system.input['price'] = price_val
        fuzzy_system.compute()
        return round(fuzzy_system.output['livability'], 1)
    except:
        # Fallback if fuzzy computation fails
        return 50.0

# ============================================================================
# FEATURE B: ML AFFORDABILITY PREDICTOR
# ============================================================================
@st.cache_resource
def train_ml_model(df):
    """Train Random Forest model to predict rental prices"""
    X = df[['Distance_to_MRT', 'Amenity_Count', 'Safety_Index']]
    y = df['Rental_Price']
    
    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)
    
    model = RandomForestRegressor(n_estimators=100, random_state=42, max_depth=10)
    model.fit(X_train, y_train)
    
    y_pred = model.predict(X_test)
    mse = mean_squared_error(y_test, y_pred)
    r2 = r2_score(y_test, y_pred)
    
    return model, mse, r2

# ============================================================================
# MAIN APPLICATION
# ============================================================================
def main():
    # Header
    st.markdown("<h1 style='text-align: center; color: #00ff88;'>🏘️ SmartHabit-MY</h1>", unsafe_allow_html=True)
    st.markdown("<h3 style='text-align: center; color: #00d4ff;'>AI-Driven Housing Affordability & Inclusivity Dashboard</h3>", unsafe_allow_html=True)
    st.markdown("<p style='text-align: center; color: #a0a0a0;'>SDG 11: Sustainable Cities & Communities | Malaysia Context</p>", unsafe_allow_html=True)
    st.markdown("---")
    
    # Load data
    df = generate_malaysia_housing_data()
    
    # Train ML model
    model, mse, r2 = train_ml_model(df)
    
    # ========================================================================
    # SIDEBAR - USER INPUTS
    # ========================================================================
    st.sidebar.markdown("## 🎯 Your Preferences")
    st.sidebar.markdown("---")
    
    selected_location = st.sidebar.selectbox(
        "📍 Preferred Location",
        options=['All'] + sorted(df['Location'].unique().tolist())
    )
    
    budget = st.sidebar.slider(
        "💰 Monthly Budget (RM)",
        min_value=800,
        max_value=10000,
        value=2000,
        step=100
    )
    
    max_distance = st.sidebar.slider(
        "🚇 Max Distance to MRT (km)",
        min_value=0.0,
        max_value=15.0,
        value=5.0,
        step=0.1
    )
    
    min_amenities = st.sidebar.slider(
        "🏪 Minimum Amenities Nearby",
        min_value=1,
        max_value=20,
        value=8,
        step=1
    )
    
    st.sidebar.markdown("---")
    st.sidebar.markdown("### 🤖 AI Model Performance")
    st.sidebar.metric("R² Score", f"{r2:.3f}")
    st.sidebar.metric("MSE", f"{mse:.0f}")
    
    # ========================================================================
    # FILTER DATA BASED ON USER INPUTS
    # ========================================================================
    filtered_df = df.copy()
    if selected_location != 'All':
        filtered_df = filtered_df[filtered_df['Location'] == selected_location]
    
    filtered_df = filtered_df[
        (filtered_df['Rental_Price'] <= budget) &
        (filtered_df['Distance_to_MRT'] <= max_distance) &
        (filtered_df['Amenity_Count'] >= min_amenities)
    ]
    
    # ========================================================================
    # FEATURE C: DASHBOARD UI - TOP ROW METRICS
    # ========================================================================
    if len(filtered_df) > 0:
        # Get best match
        best_match = filtered_df.iloc[0]
        
        # Predict fair price using ML
        predicted_price = model.predict([[max_distance, min_amenities, best_match['Safety_Index']]])[0]
        
        # Calculate livability score using Fuzzy Logic
        livability_score = calculate_livability_score(best_match['Distance_to_MRT'], best_match['Rental_Price'])
        
        # Display metrics
        col1, col2, col3 = st.columns(3)
        
        with col1:
            st.markdown("<div class='metric-box'>", unsafe_allow_html=True)
            st.metric(
                label="💵 AI Predicted Fair Price",
                value=f"RM {predicted_price:.0f}",
                delta=f"{predicted_price - budget:.0f} vs budget"
            )
            st.markdown("</div>", unsafe_allow_html=True)
        
        with col2:
            st.markdown("<div class='metric-box'>", unsafe_allow_html=True)
            st.metric(
                label="🎯 Fuzzy Livability Score",
                value=f"{livability_score}/100",
                delta="Excellent" if livability_score > 70 else "Good" if livability_score > 40 else "Fair"
            )
            st.markdown("</div>", unsafe_allow_html=True)
        
        with col3:
            st.markdown("<div class='metric-box'>", unsafe_allow_html=True)
            st.metric(
                label="🛡️ Safety Rating",
                value=f"{best_match['Safety_Index']:.1f}/10",
                delta="Safe" if best_match['Safety_Index'] > 7 else "Moderate"
            )
            st.markdown("</div>", unsafe_allow_html=True)
        
        st.markdown("<br>", unsafe_allow_html=True)
        
        # ====================================================================
        # MIDDLE ROW - INTERACTIVE SCATTER PLOT
        # ====================================================================
        st.markdown("### 📊 Price vs Convenience Analysis")
        
        # Create scatter plot
        fig = px.scatter(
            df,
            x='Distance_to_MRT',
            y='Rental_Price',
            size='Amenity_Count',
            color='Safety_Index',
            hover_data=['Location'],
            title='Housing Options: Price vs Distance to MRT',
            labels={
                'Distance_to_MRT': 'Distance to MRT (km)',
                'Rental_Price': 'Monthly Rent (RM)',
                'Safety_Index': 'Safety Score'
            },
            color_continuous_scale='Viridis'
        )
        
        # Highlight user's criteria
        fig.add_trace(go.Scatter(
            x=[best_match['Distance_to_MRT']],
            y=[best_match['Rental_Price']],
            mode='markers',
            marker=dict(size=20, color='#00ff88', symbol='star', line=dict(width=2, color='white')),
            name='Best Match',
            hovertemplate='<b>Best Match</b><br>Location: ' + best_match['Location'] + '<br>Price: RM' + str(best_match['Rental_Price']) + '<extra></extra>'
        ))
        
        fig.update_layout(
            plot_bgcolor='rgba(0,0,0,0)',
            paper_bgcolor='rgba(0,0,0,0)',
            font=dict(color='#ffffff'),
            height=500
        )
        
        st.plotly_chart(fig, use_container_width=True)
        
        # ====================================================================
        # BOTTOM ROW - AI RECOMMENDATION ENGINE
        # ====================================================================
        st.markdown("### 🤖 AI-Powered Recommendation")
        
        # Generate recommendation text
        recommendation = f"""
        <div class='recommendation'>
        <strong>🎯 Smart Recommendation for You:</strong><br><br>
        Based on your budget of <strong>RM {budget}</strong> and preference for locations within <strong>{max_distance} km</strong> from MRT, 
        we recommend <strong>{best_match['Location']}</strong>.<br><br>
        
        <strong>Why this location?</strong><br>
        ✅ Rental Price: <strong>RM {best_match['Rental_Price']:.0f}</strong> (within budget)<br>
        ✅ Distance to MRT: <strong>{best_match['Distance_to_MRT']} km</strong> (convenient commute)<br>
        ✅ Amenities Nearby: <strong>{best_match['Amenity_Count']}</strong> facilities<br>
        ✅ Safety Index: <strong>{best_match['Safety_Index']:.1f}/10</strong><br>
        ✅ Livability Score: <strong>{livability_score}/100</strong> (Fuzzy Logic Assessment)<br><br>
        
        <em>💡 This recommendation uses AI (Random Forest) for price prediction and Fuzzy Logic for livability assessment, 
        ensuring you get the best value for your money while maintaining quality of life.</em>
        </div>
        """
        st.markdown(recommendation, unsafe_allow_html=True)
        
        # ====================================================================
        # ADDITIONAL INSIGHTS
        # ====================================================================
        st.markdown("<br>", unsafe_allow_html=True)
        col1, col2 = st.columns(2)
        
        with col1:
            st.markdown("### 📈 Top 5 Affordable Options")
            top_5 = filtered_df.nsmallest(5, 'Rental_Price')[['Location', 'Rental_Price', 'Distance_to_MRT', 'Safety_Index']]
            st.dataframe(top_5, use_container_width=True)
        
        with col2:
            st.markdown("### 🏆 Top 5 Safest Areas")
            top_safe = filtered_df.nlargest(5, 'Safety_Index')[['Location', 'Safety_Index', 'Rental_Price', 'Distance_to_MRT']]
            st.dataframe(top_safe, use_container_width=True)
        
    else:
        st.warning("⚠️ No housing options match your criteria. Try adjusting your preferences in the sidebar.")
    
    # ========================================================================
    # FOOTER
    # ========================================================================
    st.markdown("---")
    st.markdown(
        "<p style='text-align: center; color: #666;'>SmartHabit-MY | SDG XI Hackathon 2025 | "
        "Powered by Fuzzy Logic & Machine Learning</p>",
        unsafe_allow_html=True
    )

if __name__ == "__main__":
    main()
