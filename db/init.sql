USE newsletter_db;

-- --------------------------------------------------------
-- subscribers 테이블: 뉴스레터 구독자 정보 관리
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    status ENUM('pending', 'verified') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    -- 이메일 인증이 완료된 시간을 기록하는 컬럼 추가
    verified_at TIMESTAMP NULL
);

-- --------------------------------------------------------
-- users 테이블: 회원 로그인 정보 관리
-- --------------------------------------------------------
-- users 테이블이 존재하지 않으면 새로 생성합니다.
CREATE TABLE IF NOT EXISTS users (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    -- 회원의 인증 상태를 관리하는 컬럼 추가
    status ENUM('pending', 'verified') NOT NULL DEFAULT 'pending',
    -- 이메일 인증에 사용될 고유 토큰을 저장하는 컬럼 추가
    token VARCHAR(255) NULL
);