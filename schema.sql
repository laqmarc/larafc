
CREATE TABLE IF NOT EXISTS leagues (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    type ENUM('league', 'cup', 'championship', 'friendly') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS seasons (
    id INT PRIMARY KEY AUTO_INCREMENT,
    league_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,           
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (league_id) REFERENCES leagues(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS teams (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    founded_year YEAR,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS stadiums (
    id INT PRIMARY KEY AUTO_INCREMENT,
    team_id INT NOT NULL,                                  
    name VARCHAR(100) NOT NULL,                           
    capacity INT NOT NULL DEFAULT 0,                      
    level INT NOT NULL DEFAULT 1,                          
    pitch_type ENUM('grass','artificial','hybrid') NOT NULL DEFAULT 'grass',
    construction_cost DECIMAL(12,2) DEFAULT 0,             
    maintenance_cost DECIMAL(12,2) DEFAULT 0,              
    location VARCHAR(255),                                 
    address VARCHAR(255),                                 
    built_year YEAR,                                      
    total_land_area DECIMAL(10,2) DEFAULT 0,              
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (team_id) REFERENCES teams(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS stadium_land_plots (
    id INT PRIMARY KEY AUTO_INCREMENT,
    stadium_id INT NOT NULL,                               
    name VARCHAR(100),                                     
    area DECIMAL(10,2) NOT NULL,                          
    price DECIMAL(12,2) NOT NULL,                         
    is_acquired BOOLEAN NOT NULL DEFAULT FALSE,           
    acquired_at TIMESTAMP NULL,                            
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (stadium_id) REFERENCES stadiums(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS stadium_expansions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    stadium_id INT NOT NULL,
    expansion_type ENUM(
        'capacity','facility','luxury','roof','pitch_upgrade'
    ) NOT NULL,                                            
    description VARCHAR(255),                             
    cost DECIMAL(12,2) NOT NULL,                           
    capacity_increase INT DEFAULT 0,                       
    area_increase DECIMAL(10,2) DEFAULT 0,                 
    status ENUM('planned','in_progress','completed','cancelled')
        NOT NULL DEFAULT 'planned',
    started_at DATETIME NULL,                              
    completed_at DATETIME NULL,                           
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (stadium_id) REFERENCES stadiums(id)
) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS players (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name   VARCHAR(50) NOT NULL,
    last_name    VARCHAR(50) NOT NULL,
    dob          DATE,
    nationality  VARCHAR(50),
    height_cm    SMALLINT UNSIGNED,               
    weight_kg    SMALLINT UNSIGNED,              
    preferred_foot ENUM('left','right','both')    
        DEFAULT 'right',
    photo_url    VARCHAR(255),                    
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
                 ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS player_positions (
    player_id INT NOT NULL,
    position_id TINYINT NOT NULL,
    is_primary BOOLEAN NOT NULL DEFAULT FALSE,
    preference_order TINYINT UNSIGNED DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (player_id, position_id),
    FOREIGN KEY (player_id)   REFERENCES players(id)   ON DELETE CASCADE,
    FOREIGN KEY (position_id) REFERENCES positions(id) ON DELETE CASCADE
) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS positions (
    id TINYINT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(4) NOT NULL UNIQUE,       
    name VARCHAR(50) NOT NULL,             
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Omplim-la amb totes les posicions:
INSERT INTO positions (code, name) VALUES
  ('GK',  'Goalkeeper'),
  ('SW',  'Sweeper'),
  ('RB',  'Right back'),
  ('RWB', 'Right wing-back'),
  ('CB',  'Center back'),
  ('LWB', 'Left wing-back'),
  ('LB',  'Left back'),
  ('CDM', 'Defensive midfielder'),
  ('CM',  'Central midfielder'),
  ('CAM', 'Attacking midfielder'),
  ('LM',  'Left midfielder'),
  ('RM',  'Right midfielder'),
  ('LW',  'Left winger'),
  ('RW',  'Right winger'),
  ('CF',  'Centre forward'),
  ('ST',  'Striker'),
  ('SS',  'Second striker'),
  ('WF',  'Withdrawn forward');


  CREATE TABLE IF NOT EXISTS team_players (
    id INT PRIMARY KEY AUTO_INCREMENT,
    team_id INT NOT NULL,
    player_id INT NOT NULL,
    season_id INT NOT NULL,
    shirt_number SMALLINT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    signed_at DATE,
    released_at DATE,
    contract_type ENUM('permanent','loan','youth') DEFAULT 'permanent',
    salary DECIMAL(12,2),
    FOREIGN KEY (team_id)    REFERENCES teams(id),
    FOREIGN KEY (player_id)  REFERENCES players(id),
    FOREIGN KEY (season_id)  REFERENCES seasons(id),
    UNIQUE KEY uq_tp (team_id, player_id, season_id)
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS player_loans (
    id             INT PRIMARY KEY AUTO_INCREMENT,
    team_player_id INT NOT NULL,
    to_team_id     INT NOT NULL,
    start_date     DATE    NOT NULL,
    end_date       DATE    NOT NULL,
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (team_player_id) REFERENCES team_players(id) ON DELETE CASCADE,
    FOREIGN KEY (to_team_id)       REFERENCES teams(id)
) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS injuries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    player_id INT NOT NULL,
    injury_type VARCHAR(100) NOT NULL,               
    severity ENUM('minor','moderate','serious') NOT NULL,
    injured_on DATE NOT NULL,
    expected_return DATE,
    actual_return DATE,
    cause ENUM('match','training','other') DEFAULT 'match',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (player_id) REFERENCES players(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS player_medical_reports (
    id INT PRIMARY KEY AUTO_INCREMENT,
    player_id INT NOT NULL,
    report_date DATE NOT NULL,
    condition_summary TEXT,
    fitness_level TINYINT UNSIGNED DEFAULT 100,    
    notes TEXT,
    FOREIGN KEY (player_id) REFERENCES players(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS player_form (
    id INT PRIMARY KEY AUTO_INCREMENT,
    player_id INT NOT NULL,
    season_id INT NOT NULL,
    match_id INT,                            
    form_score TINYINT UNSIGNED DEFAULT 50, 
    morale ENUM('very_low','low','normal','high','very_high') DEFAULT 'normal',
    fatigue_level TINYINT UNSIGNED DEFAULT 0, 
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (player_id) REFERENCES players(id),
    FOREIGN KEY (season_id) REFERENCES seasons(id),
    FOREIGN KEY (match_id) REFERENCES matches(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS training_sessions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    team_id INT NOT NULL,
    session_date DATE NOT NULL,
    session_type ENUM('general','fitness','tactics','set_pieces','goalkeeper','individual') NOT NULL,
    intensity ENUM('low','medium','high') DEFAULT 'medium',
    duration_minutes SMALLINT UNSIGNED DEFAULT 90,
    notes TEXT,
    FOREIGN KEY (team_id) REFERENCES teams(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS player_trainings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    training_session_id INT NOT NULL,
    player_id INT NOT NULL,
    performance_score TINYINT UNSIGNED DEFAULT 50,  
    fatigue_added TINYINT UNSIGNED DEFAULT 10,      
    injury_risk TINYINT UNSIGNED DEFAULT 5,         
    notes TEXT,
    FOREIGN KEY (training_session_id) REFERENCES training_sessions(id) ON DELETE CASCADE,
    FOREIGN KEY (player_id) REFERENCES players(id)
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS recovery_sessions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    player_id INT NOT NULL,
    session_date DATE NOT NULL,
    recovery_type ENUM('physiotherapy','massage','ice_bath','rest_day') NOT NULL,
    fatigue_recovered TINYINT UNSIGNED DEFAULT 10,
    morale_boost TINYINT UNSIGNED DEFAULT 5,
    notes TEXT,
    FOREIGN KEY (player_id) REFERENCES players(id)
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS matches (
    id INT PRIMARY KEY AUTO_INCREMENT,
    season_id INT NOT NULL,
    match_date DATETIME NOT NULL,
    home_team_id INT NOT NULL,
    away_team_id INT NOT NULL,
    stadium_id INT NOT NULL,
    status ENUM('scheduled','in_progress','completed') NOT NULL DEFAULT 'scheduled',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (season_id) REFERENCES seasons(id),
    FOREIGN KEY (home_team_id) REFERENCES teams(id),
    FOREIGN KEY (away_team_id) REFERENCES teams(id),
    FOREIGN KEY (stadium_id) REFERENCES stadiums(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS match_results (
    match_id INT PRIMARY KEY,
    home_score TINYINT UNSIGNED DEFAULT 0,
    away_score TINYINT UNSIGNED DEFAULT 0,
    winner_team_id INT NULL, 
    extra_time_played BOOLEAN DEFAULT FALSE,
    penalties_played BOOLEAN DEFAULT FALSE,
    home_penalties TINYINT UNSIGNED DEFAULT NULL,
    away_penalties TINYINT UNSIGNED DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (match_id) REFERENCES matches(id) ON DELETE CASCADE,
    FOREIGN KEY (winner_team_id) REFERENCES teams(id)
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS match_events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    match_id INT NOT NULL,
    team_id INT NOT NULL,
    player_id INT,  
    event_type ENUM('goal','assist','yellow_card','red_card','substitution') NOT NULL,
    event_minute TINYINT UNSIGNED NOT NULL,
    related_player_id INT,
    event_extra_info VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (match_id) REFERENCES matches(id) ON DELETE CASCADE,
    FOREIGN KEY (team_id) REFERENCES teams(id),
    FOREIGN KEY (player_id) REFERENCES players(id),
    FOREIGN KEY (related_player_id) REFERENCES players(id)
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS matchdays (
    id INT PRIMARY KEY AUTO_INCREMENT,
    season_id INT NOT NULL,
    round_number INT NOT NULL,
    name VARCHAR(50),
    start_date DATE,
    end_date DATE,
    FOREIGN KEY (season_id) REFERENCES seasons(id)
) ENGINE=InnoDB;

ALTER TABLE matches ADD COLUMN matchday_id INT NULL;
ALTER TABLE matches ADD FOREIGN KEY (matchday_id) REFERENCES matchdays(id);



CREATE TABLE IF NOT EXISTS player_match_stats (
    id INT PRIMARY KEY AUTO_INCREMENT,
    match_id INT NOT NULL,
    player_id INT NOT NULL,
    team_id INT NOT NULL,
    minutes_played TINYINT UNSIGNED DEFAULT 0,
    goals TINYINT UNSIGNED DEFAULT 0,
    assists TINYINT UNSIGNED DEFAULT 0,
    yellow_cards TINYINT UNSIGNED DEFAULT 0,
    red_cards TINYINT UNSIGNED DEFAULT 0,
    is_starter BOOLEAN DEFAULT TRUE,
    is_substituted BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (match_id) REFERENCES matches(id),
    FOREIGN KEY (player_id) REFERENCES players(id),
    FOREIGN KEY (team_id) REFERENCES teams(id)
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS standings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    season_id INT NOT NULL,
    team_id INT NOT NULL,
    played SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    won SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    draw SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    lost SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    goals_for SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    goals_against SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    goal_diff SMALLINT NOT NULL DEFAULT 0, -- canviat
    points SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    rank SMALLINT UNSIGNED DEFAULT NULL,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (season_id) REFERENCES seasons(id),
    FOREIGN KEY (team_id) REFERENCES teams(id),
    UNIQUE KEY (season_id, team_id)
) ENGINE=InnoDB;

------------------------------------------------------

CREATE TABLE IF NOT EXISTS staff (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    role ENUM('coach','assistant','physio','scout','director'),
    nationality VARCHAR(50),
    birth_date DATE,
    team_id INT,
    salary DECIMAL(12,2),
    start_date DATE,
    end_date DATE,
    FOREIGN KEY (team_id) REFERENCES teams(id)
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS team_budgets (
    team_id INT PRIMARY KEY,
    season_id INT NOT NULL,
    balance DECIMAL(14,2) NOT NULL DEFAULT 0,
    wage_budget DECIMAL(14,2) DEFAULT 0,
    transfer_budget DECIMAL(14,2) DEFAULT 0,
    FOREIGN KEY (team_id) REFERENCES teams(id),
    FOREIGN KEY (season_id) REFERENCES seasons(id)
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS financial_transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    team_id INT NOT NULL,
    season_id INT NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    description VARCHAR(255),
    transaction_type ENUM('income', 'expense'),
    category ENUM('ticketing','merchandising','salaries','transfers','sponsorship','misc'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (team_id) REFERENCES teams(id),
    FOREIGN KEY (season_id) REFERENCES seasons(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS sponsors (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    sponsor_type ENUM('shirt','stadium','general') NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS team_sponsors (
    team_id INT,
    sponsor_id INT,
    PRIMARY KEY (team_id, sponsor_id),
    FOREIGN KEY (team_id) REFERENCES teams(id),
    FOREIGN KEY (sponsor_id) REFERENCES sponsors(id)
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS transfers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    player_id INT NOT NULL,
    from_team_id INT,
    to_team_id INT,
    transfer_date DATE NOT NULL,
    transfer_fee DECIMAL(12,2),
    season_id INT NOT NULL,
    FOREIGN KEY (player_id) REFERENCES players(id),
    FOREIGN KEY (from_team_id) REFERENCES teams(id),
    FOREIGN KEY (to_team_id) REFERENCES teams(id),
    FOREIGN KEY (season_id) REFERENCES seasons(id)
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS scouts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    nationality VARCHAR(50),
    experience_level TINYINT UNSIGNED DEFAULT 1, -- 1 a 10, per exemple
    assigned_team_id INT,
    FOREIGN KEY (assigned_team_id) REFERENCES teams(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS scouting_reports (
    id INT PRIMARY KEY AUTO_INCREMENT,
    scout_id INT NOT NULL,
    player_id INT NOT NULL,
    report_date DATE NOT NULL,
    technical_rating TINYINT UNSIGNED,   -- 0-100
    mental_rating TINYINT UNSIGNED,
    physical_rating TINYINT UNSIGNED,
    potential_rating TINYINT UNSIGNED,   -- potencial futur
    notes TEXT,
    FOREIGN KEY (scout_id) REFERENCES scouts(id),
    FOREIGN KEY (player_id) REFERENCES players(id)
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS team_profiles (
    team_id INT PRIMARY KEY,
    play_style ENUM('attacking','defensive','balanced') DEFAULT 'balanced',
    aggression_level TINYINT UNSIGNED DEFAULT 50,   -- 0 a 100
    pressing_level TINYINT UNSIGNED DEFAULT 50,
    mentality ENUM('cautious','standard','positive') DEFAULT 'standard',
    FOREIGN KEY (team_id) REFERENCES teams(id)
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS fanbase (
    team_id INT PRIMARY KEY,
    size INT UNSIGNED DEFAULT 1000,
    loyalty TINYINT UNSIGNED DEFAULT 50,   -- 0 a 100
    mood ENUM('angry','neutral','happy') DEFAULT 'neutral',
    FOREIGN KEY (team_id) REFERENCES teams(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS attendance_history (
    id INT PRIMARY KEY AUTO_INCREMENT,
    match_id INT NOT NULL,
    attendance INT UNSIGNED DEFAULT 0,
    ticket_revenue DECIMAL(12,2) DEFAULT 0,
    FOREIGN KEY (match_id) REFERENCES matches(id)
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS youth_players (
    id INT PRIMARY KEY AUTO_INCREMENT,
    team_id INT NOT NULL,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    dob DATE,
    potential_rating TINYINT UNSIGNED,
    current_rating TINYINT UNSIGNED,
    position_code VARCHAR(4),
    promoted_to_senior BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (team_id) REFERENCES teams(id)
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS media_articles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255),
    content TEXT,
    published_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    related_team_id INT,
    related_player_id INT,
    type ENUM('news','rumor','interview','announcement') DEFAULT 'news',
    FOREIGN KEY (related_team_id) REFERENCES teams(id),
    FOREIGN KEY (related_player_id) REFERENCES players(id)
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS transfer_clauses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    transfer_id INT NOT NULL,
    clause_type ENUM('buy_back','sell_on','release','loan_fee') NOT NULL,
    value DECIMAL(12,2),
    notes TEXT,
    FOREIGN KEY (transfer_id) REFERENCES transfers(id)
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255),
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS user_teams (
    user_id INT NOT NULL,
    team_id INT NOT NULL,
    season_id INT NOT NULL,
    PRIMARY KEY (user_id, team_id, season_id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (team_id) REFERENCES teams(id),
    FOREIGN KEY (season_id) REFERENCES seasons(id)
) ENGINE=InnoDB;
