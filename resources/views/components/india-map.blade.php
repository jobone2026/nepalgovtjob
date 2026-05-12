@props(['states'])

<div class="india-map-container">
    <style>
        .india-map-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 24px;
            margin: 24px 0;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        
        .map-header {
            text-align: center;
            margin-bottom: 24px;
        }
        
        .map-title {
            font-size: 28px;
            font-weight: 800;
            color: white;
            margin-bottom: 8px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .map-subtitle {
            font-size: 14px;
            color: rgba(255,255,255,0.9);
        }
        
        .india-map-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 12px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .state-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }
        
        .state-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        
        .state-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            border-color: #667eea;
        }
        
        .state-card:hover::before {
            transform: scaleX(1);
        }
        
        .state-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            font-size: 24px;
        }
        
        .state-name {
            font-size: 16px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 8px;
            line-height: 1.3;
        }
        
        .state-stats {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .job-count {
            font-size: 24px;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .job-label {
            font-size: 12px;
            color: #718096;
            font-weight: 600;
        }
        
        .all-india-card {
            grid-column: 1 / -1;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 24px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            box-shadow: 0 8px 24px rgba(245, 87, 108, 0.3);
        }
        
        .all-india-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(245, 87, 108, 0.4);
        }
        
        .all-india-content {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .all-india-icon {
            width: 64px;
            height: 64px;
            background: rgba(255,255,255,0.2);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
        }
        
        .all-india-text h3 {
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 4px;
        }
        
        .all-india-text p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .all-india-count {
            font-size: 48px;
            font-weight: 900;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        @media (max-width: 768px) {
            .india-map-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 8px;
            }
            
            .state-card {
                padding: 12px;
            }
            
            .state-icon {
                width: 40px;
                height: 40px;
                font-size: 20px;
            }
            
            .state-name {
                font-size: 14px;
            }
            
            .job-count {
                font-size: 20px;
            }
            
            .all-india-card {
                flex-direction: column;
                text-align: center;
                gap: 16px;
            }
            
            .all-india-count {
                font-size: 36px;
            }
            
            .map-title {
                font-size: 22px;
            }
        }
    </style>
    
    <div class="map-header">
        <h2 class="map-title">🇮🇳 Jobs Across India</h2>
        <p class="map-subtitle">Explore government job opportunities in every state</p>
    </div>
    
    <div class="india-map-grid">
        <!-- All India Card -->
        <a href="{{ route('posts.jobs') }}" class="all-india-card" style="text-decoration: none; color: inherit;">
            <div class="all-india-content">
                <div class="all-india-icon">🇮🇳</div>
                <div class="all-india-text">
                    <h3>All India Jobs</h3>
                    <p>Pan-India opportunities</p>
                </div>
            </div>
            <div class="all-india-count">
                {{ number_format($states->where('id', null)->first()->posts_count ?? 0) }}
            </div>
        </a>
        
        <!-- State Cards -->
        @foreach($states->where('id', '!=', null)->sortByDesc('posts_count') as $state)
            <a href="{{ route('states.show', $state) }}" class="state-card" style="text-decoration: none; color: inherit;">
                <div class="state-icon">
                    @php
                        $stateEmojis = [
                            'Andhra Pradesh' => '🏛️',
                            'Arunachal Pradesh' => '🏔️',
                            'Assam' => '🍵',
                            'Bihar' => '📚',
                            'Chhattisgarh' => '🌾',
                            'Goa' => '🏖️',
                            'Gujarat' => '🦁',
                            'Haryana' => '🌾',
                            'Himachal Pradesh' => '⛰️',
                            'Jharkhand' => '⛏️',
                            'Karnataka' => '💻',
                            'Kerala' => '🥥',
                            'Madhya Pradesh' => '🐅',
                            'Maharashtra' => '🏙️',
                            'Manipur' => '🎭',
                            'Meghalaya' => '☔',
                            'Mizoram' => '🌲',
                            'Nagaland' => '🎪',
                            'Odisha' => '🏛️',
                            'Punjab' => '🌾',
                            'Rajasthan' => '🐪',
                            'Sikkim' => '🏔️',
                            'Tamil Nadu' => '🕉️',
                            'Telangana' => '💎',
                            'Tripura' => '🎋',
                            'Uttar Pradesh' => '🕌',
                            'Uttarakhand' => '🏔️',
                            'West Bengal' => '🐯',
                            'Andaman and Nicobar Islands' => '🏝️',
                            'Chandigarh' => '🏛️',
                            'Dadra and Nagar Haveli and Daman and Diu' => '🌴',
                            'Delhi' => '🏛️',
                            'Jammu and Kashmir' => '🏔️',
                            'Ladakh' => '🏔️',
                            'Lakshadweep' => '🏝️',
                            'Puducherry' => '🏖️'
                        ];
                        $emoji = $stateEmojis[$state->name] ?? '📍';
                    @endphp
                    {{ $emoji }}
                </div>
                <div class="state-name">{{ $state->name }}</div>
                <div class="state-stats">
                    <span class="job-count">{{ number_format($state->posts_count) }}</span>
                    <span class="job-label">Jobs</span>
                </div>
            </a>
        @endforeach
    </div>
</div>
