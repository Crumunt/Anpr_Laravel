<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
    
    :root {
        --clsu-green: #006300;
        --clsu-light-green: #068406;
        --clsu-dark-green: #004d00;
        --clsu-accent: #f3c423;
        --clsu-hover: #068406;
        --gradient-primary: linear-gradient(135deg, #006300 0%, #068406 100%);
        --gradient-secondary: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        --gradient-accent: linear-gradient(135deg, #f3c423 0%, #f59e0b 100%);
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        min-height: 100vh;
    }
    
    .clsu-bg { background: var(--gradient-primary); }
    .clsu-text { color: var(--clsu-green); }
    .clsu-accent-bg { background: var(--gradient-accent); }
    .clsu-accent-text { color: var(--clsu-accent); }
 
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        box-shadow: var(--shadow-lg);
        border-radius: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .glass-card:hover {
        box-shadow: var(--shadow-2xl);
        transform: translateY(-8px);
        border-color: rgba(0, 99, 0, 0.3);
    }
    
    .progress-bar {
        transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    
    .analytics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
        padding: 1rem;
    }
    
    .analytics-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 1.25rem;
        padding: 1.5rem;
        box-shadow: var(--shadow-lg);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        position: relative;
        overflow: hidden;
    }
    
    .analytics-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-primary);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .analytics-card:hover::before {
        opacity: 1;
    }
    
    .analytics-card:hover {
        box-shadow: var(--shadow-2xl);
        transform: translateY(-8px);
        border-color: rgba(0, 99, 0, 0.3);
    }
    
    .chart-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        position: relative;
    }
    
    .chart-title i {
        margin-right: 0.75rem;
        color: var(--clsu-green);
        font-size: 1.5rem;
        background: linear-gradient(135deg, var(--clsu-green), var(--clsu-light-green));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .metric-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid rgba(226, 232, 240, 0.6);
        transition: all 0.3s ease;
    }
    
    .metric-item:last-child {
        border-bottom: none;
    }
    
    .metric-item:hover {
        background: rgba(0, 99, 0, 0.02);
        border-radius: 0.5rem;
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
    
    .metric-label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
    }
    
    .metric-value {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1f2937;
    }
    
    .metric-change {
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
        border-radius: 0.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .metric-change.positive {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        color: #166534;
        box-shadow: 0 2px 4px rgba(34, 197, 94, 0.2);
    }
    
    .metric-change.negative {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
        box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2);
    }
    
    .metric-change.neutral {
        background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
        color: #374151;
        box-shadow: 0 2px 4px rgba(107, 114, 128, 0.2);
    }
    
    /* Analytics Stats Grid */
    .analytics-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }
    
    /* Analytics Table Container */
    .analytics-table-container {
        margin-top: 2rem;
    }
    
    /* Enhanced Chart Cards */
    .enhanced-chart-card {
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(226, 232, 240, 0.8);
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 250, 252, 0.95) 100%);
        backdrop-filter: blur(20px);
        border-radius: 1.25rem;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .enhanced-chart-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #006300, #3b82f6, #8b5cf6, #ef4444);
        opacity: 0.8;
    }
    
    .enhanced-chart-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(0, 99, 0, 0.02), rgba(59, 130, 246, 0.02));
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
    }
    
    .enhanced-chart-card:hover::after {
        opacity: 1;
    }
    
    .enhanced-chart-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-2xl);
        border-color: rgba(0, 99, 0, 0.3);
    }
    
    /* Chart Header */
    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.75rem 1.75rem 1.25rem 1.75rem;
        border-bottom: 1px solid rgba(226, 232, 240, 0.6);
        background: linear-gradient(135deg, rgba(248, 250, 252, 0.8), rgba(255, 255, 255, 0.8));
    }
    
    .chart-actions {
        display: flex;
        gap: 0.75rem;
    }
    
    .chart-action-btn {
        width: 40px;
        height: 40px;
        border: none;
        background: linear-gradient(135deg, rgba(0, 99, 0, 0.1), rgba(0, 99, 0, 0.15));
        color: var(--clsu-green);
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 1rem;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }
    
    .chart-action-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        transition: left 0.6s ease;
    }
    
    .chart-action-btn:hover::before {
        left: 100%;
    }
    
    .chart-action-btn:hover {
        background: linear-gradient(135deg, rgba(0, 99, 0, 0.2), rgba(0, 99, 0, 0.25));
        transform: scale(1.1) translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    /* Enhanced Chart Container */
    .enhanced-chart {
        padding: 1.25rem 1.75rem;
        position: relative;
        min-height: 300px;
    }
    
    .enhanced-chart::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(0, 99, 0, 0.02), rgba(59, 130, 246, 0.02));
        pointer-events: none;
        border-radius: 0.75rem;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .enhanced-chart:hover::before {
        opacity: 1;
    }
    
    /* Chart Metrics */
    .chart-metrics {
        padding: 1.25rem 1.75rem 1.75rem 1.75rem;
        background: linear-gradient(135deg, rgba(248, 250, 252, 0.8), rgba(255, 255, 255, 0.8));
        border-top: 1px solid rgba(226, 232, 240, 0.6);
    }
    
    .enhanced-metric {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-radius: 0.75rem;
        margin-bottom: 0.75rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: rgba(255, 255, 255, 0.8);
        border: 1px solid rgba(226, 232, 240, 0.4);
        box-shadow: var(--shadow-sm);
    }
    
    .enhanced-metric:hover {
        background: linear-gradient(135deg, rgba(0, 99, 0, 0.05), rgba(0, 99, 0, 0.08));
        border-color: rgba(0, 99, 0, 0.3);
        transform: translateX(8px) scale(1.02);
        box-shadow: var(--shadow-md);
    }
    
    .enhanced-metric:last-child {
        margin-bottom: 0;
    }
    
    .metric-icon {
        width: 48px;
        height: 48px;
        border-radius: 0.75rem;
        background: linear-gradient(135deg, rgba(0, 99, 0, 0.1), rgba(0, 99, 0, 0.15));
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        flex-shrink: 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: var(--shadow-sm);
    }
    
    .enhanced-metric:hover .metric-icon {
        transform: scale(1.1) rotate(5deg);
        background: linear-gradient(135deg, rgba(0, 99, 0, 0.2), rgba(0, 99, 0, 0.25));
        box-shadow: var(--shadow-md);
    }
    
    .metric-content {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .metric-label {
        font-size: 0.75rem;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .metric-value {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1f2937;
        margin-top: 0.25rem;
        transition: all 0.3s ease;
    }
    
    .enhanced-metric:hover .metric-value {
        color: var(--clsu-green);
        transform: scale(1.05);
    }
    
    /* Enhanced Chart Title */
    .chart-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
        display: flex;
        align-items: center;
        position: relative;
    }
    
    .chart-title i {
        margin-right: 0.875rem;
        color: var(--clsu-green);
        font-size: 1.5rem;
        width: 32px;
        text-align: center;
        background: linear-gradient(135deg, var(--clsu-green), var(--clsu-light-green));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .chart-title::after {
        content: '';
        position: absolute;
        bottom: -0.75rem;
        left: 0;
        width: 3rem;
        height: 3px;
        background: var(--gradient-primary);
        border-radius: 2px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .enhanced-chart-card:hover .chart-title::after {
        opacity: 1;
    }
    
    /* Loading Animation */
    .chart-loading {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 10;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
    }
    
    .loading-spinner {
        width: 48px;
        height: 48px;
        border: 4px solid rgba(0, 99, 0, 0.1);
        border-top: 4px solid var(--clsu-green);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-bottom: 1rem;
    }
    
    .loading-text {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
        margin: 0;
        animation: pulse 2s infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Pulse Animation */
    .pulse-animation {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(0, 99, 0, 0.4);
        }
        70% {
            transform: scale(1.05);
            box-shadow: 0 0 0 12px rgba(0, 99, 0, 0);
        }
        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(0, 99, 0, 0);
        }
    }
    
    /* Enhanced Chart Modal */
    .chart-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.85);
        backdrop-filter: blur(20px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        cursor: pointer;
        padding: 2rem;
    }
    
    .chart-modal-content {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95));
        backdrop-filter: blur(20px);
        border-radius: 1.5rem;
        width: 95%;
        height: 95%;
        max-width: 1600px;
        max-height: 1000px;
        display: flex;
        flex-direction: column;
        box-shadow: var(--shadow-2xl);
        animation: modalSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: default;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(-30px) rotateX(-10deg);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0) rotateX(0deg);
        }
    }
    
    .chart-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 2rem 2.5rem;
        border-bottom: 1px solid rgba(226, 232, 240, 0.6);
        background: linear-gradient(135deg, rgba(248, 250, 252, 0.9), rgba(255, 255, 255, 0.9));
        flex-shrink: 0;
        position: relative;
    }
    
    .chart-modal-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-primary);
    }
    
    .chart-modal-header h3 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
        background: linear-gradient(135deg, var(--clsu-green), var(--clsu-light-green));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .close-chart-modal-btn {
        width: 48px;
        height: 48px;
        border: none;
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.15));
        color: #ef4444;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 1.25rem;
        box-shadow: var(--shadow-sm);
    }
    
    .close-chart-modal-btn:hover {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(239, 68, 68, 0.25));
        transform: scale(1.1) rotate(90deg);
        box-shadow: var(--shadow-md);
    }
    
    .chart-modal-body {
        flex: 1;
        padding: 2.5rem;
        overflow-y: auto;
        position: relative;
    }
    
    .chart-modal-body .analytics-card {
        height: 100%;
        display: flex;
        flex-direction: column;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(20px);
    }
    
    .chart-modal-body .chart-container {
        flex: 1;
        min-height: 500px;
    }
    
    .chart-modal-body .chart-metrics {
        flex-shrink: 0;
    }
    
    /* Enhanced Chart Animations */
    .enhanced-chart-card {
        animation: chartFadeIn 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    @keyframes chartFadeIn {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    .enhanced-chart-card:nth-child(1) { animation-delay: 0.1s; }
    .enhanced-chart-card:nth-child(2) { animation-delay: 0.2s; }
    .enhanced-chart-card:nth-child(3) { animation-delay: 0.3s; }
    .enhanced-chart-card:nth-child(4) { animation-delay: 0.4s; }
    
    /* Enhanced Metric Hover Effects */
    .enhanced-metric:hover .metric-icon {
        transform: scale(1.1) rotate(5deg);
        background: linear-gradient(135deg, rgba(0, 99, 0, 0.2), rgba(0, 99, 0, 0.25));
    }
    
    .enhanced-metric:hover .metric-value {
        color: var(--clsu-green);
        transform: scale(1.05);
    }
    
    /* Chart Action Button Enhancements */
    .chart-action-btn {
        position: relative;
        overflow: hidden;
    }
    
    .chart-action-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        transition: left 0.6s ease;
    }
    
    .chart-action-btn:hover::before {
        left: 100%;
    }
    
    /* Enhanced Chart Container */
    .enhanced-chart {
        position: relative;
        overflow: hidden;
    }
    
    .enhanced-chart::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(0, 99, 0, 0.02), rgba(59, 130, 246, 0.02));
        pointer-events: none;
        border-radius: 0.75rem;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .enhanced-chart:hover::after {
        opacity: 1;
    }
    
    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .analytics-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
            padding: 1rem;
        }
        
        .chart-modal {
            padding: 1rem;
        }
        
        .chart-modal-content {
            width: 100%;
            height: 100%;
            border-radius: 1rem;
        }
        
        .chart-modal-header {
            padding: 1.5rem;
        }
        
        .chart-modal-body {
            padding: 1.5rem;
        }
        
        .chart-modal-header h3 {
            font-size: 1.25rem;
        }
        
        .enhanced-chart-card {
            margin-bottom: 1rem;
        }
    }
    
    /* Focus styles for accessibility */
    a:focus, button:focus {
        outline: 2px solid var(--clsu-accent);
        outline-offset: 2px;
    }
    
    /* Skip to main content for accessibility */
    .skip-link {
        position: absolute;
        top: -40px;
        left: 0;
        background: var(--clsu-accent);
        color: black;
        padding: 8px;
        z-index: 100;
        transition: top 0.3s;
    }
    
    .skip-link:focus {
        top: 0;
    }
    
    /* Custom scrollbar for modal */
    .chart-modal-body::-webkit-scrollbar {
        width: 8px;
    }
    
    .chart-modal-body::-webkit-scrollbar-track {
        background: rgba(226, 232, 240, 0.5);
        border-radius: 4px;
    }
    
    .chart-modal-body::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, var(--clsu-green), var(--clsu-light-green));
        border-radius: 4px;
    }
    
    .chart-modal-body::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, var(--clsu-light-green), var(--clsu-green));
    }
</style> 