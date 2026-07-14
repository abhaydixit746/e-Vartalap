-- ============================================================
-- e-Vartalap PHP — Database Schema + Seed Data
-- MySQL 8.x
-- Run: mysql -u root -p < database/schema.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS evartalap
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE evartalap;

-- ---- Users ----
CREATE TABLE IF NOT EXISTS users (
  id          BIGINT       NOT NULL AUTO_INCREMENT,
  username    VARCHAR(50)  NOT NULL,
  password    VARCHAR(255) NOT NULL COMMENT 'BCrypt hash',
  email       VARCHAR(100) NOT NULL,
  first_name  VARCHAR(100) NOT NULL,
  last_name   VARCHAR(100) NOT NULL,
  contact     VARCHAR(20)  DEFAULT NULL,
  company     VARCHAR(150) DEFAULT NULL,
  designation VARCHAR(150) DEFAULT NULL,
  photo_path  VARCHAR(500) DEFAULT NULL,
  role        ENUM('USER','ADMIN') NOT NULL DEFAULT 'USER',
  is_active   TINYINT(1)   NOT NULL DEFAULT 1,
  created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_username (username),
  UNIQUE KEY uq_email    (email),
  KEY idx_username (username),
  KEY idx_email    (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---- Questions ----
CREATE TABLE IF NOT EXISTS questions (
  id          BIGINT       NOT NULL AUTO_INCREMENT,
  title       VARCHAR(500) NOT NULL,
  body        TEXT         DEFAULT NULL,
  author_id   BIGINT       NOT NULL,
  status      ENUM('PENDING','APPROVED','REJECTED') NOT NULL DEFAULT 'PENDING',
  view_count  INT          NOT NULL DEFAULT 0,
  created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_q_status  (status),
  KEY idx_q_author  (author_id),
  KEY idx_q_created (created_at),
  CONSTRAINT fk_q_author FOREIGN KEY (author_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---- Answers ----
CREATE TABLE IF NOT EXISTS answers (
  id          BIGINT    NOT NULL AUTO_INCREMENT,
  body        TEXT      NOT NULL,
  author_id   BIGINT    NOT NULL,
  question_id BIGINT    NOT NULL,
  status      ENUM('PENDING','APPROVED','REJECTED') NOT NULL DEFAULT 'PENDING',
  is_accepted TINYINT(1) NOT NULL DEFAULT 0,
  created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_a_question (question_id),
  KEY idx_a_status   (status),
  KEY idx_a_author   (author_id),
  CONSTRAINT fk_a_author   FOREIGN KEY (author_id)   REFERENCES users(id),
  CONSTRAINT fk_a_question FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---- Tags ----
CREATE TABLE IF NOT EXISTS tags (
  id   BIGINT      NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_tag_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---- Question ↔ Tags ----
CREATE TABLE IF NOT EXISTS question_tags (
  question_id BIGINT NOT NULL,
  tag_id      BIGINT NOT NULL,
  PRIMARY KEY (question_id, tag_id),
  CONSTRAINT fk_qt_question FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE,
  CONSTRAINT fk_qt_tag      FOREIGN KEY (tag_id)      REFERENCES tags(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- SEED DATA
-- Passwords are BCrypt(cost=10) hashes:
--   admin   → Admin@123
--   others  → Pass@1234
-- ============================================================

INSERT INTO users (username,password,email,first_name,last_name,contact,company,designation,role) VALUES
('admin',  '$2y$10$7QyKL2DXEgQloKT/s3JrO.9z6SRmkavrGfTsR0Jk16XjajL9hp8NK', 'admin@evartalap.com',  'Admin',   'User',   '+91 9000000000', 'e-Vartalap',     'Administrator',       'ADMIN'),
('abhay',  '$2y$10$7QyKL2DXEgQloKT/s3JrO.9z6SRmkavrGfTsR0Jk16XjajL9hp8NK', 'abhay@gmail.com',   'Abhay',   'Sharma', '+91 9111111111', 'TechCorp India', 'Java Developer',      'USER'),
('vijay',    '$2y$10$7QyKL2DXEgQloKT/s3JrO.9z6SRmkavrGfTsR0Jk16XjajL9hp8NK', 'vijay@gmail.com',     'Vijay',     'Patel',  '+91 9222222222', 'StartupHub',     'Full Stack Developer','USER'),
('sanjay','$2y$10$7QyKL2DXEgQloKT/s3JrO.9z6SRmkavrGfTsR0Jk16XjajL9hp8NK', 'sanjay@gmail.com',    'sanjay', 'Singh',  '+91 9333333333', 'DevHouse',       'Backend Engineer',    'USER'),
('mukesh',  '$2y$10$7QyKL2DXEgQloKT/s3JrO.9z6SRmkavrGfTsR0Jk16XjajL9hp8NK', 'mukesh@gmail.com',   'Mukesh',   'Mehta',  '+91 9444444444', 'CloudSoft',      'DevOps Engineer',     'USER'),
('Jay',   '$2y$10$7QyKL2DXEgQloKT/s3JrO.9z6SRmkavrGfTsR0Jk16XjajL9hp8NK', 'Jay@gmail.com',    'Jay',    'Kumar',  '+91 9555555555', 'OpenSource Labs', 'Software Architect', 'USER');

INSERT INTO questions (title,body,author_id,status,view_count,created_at) VALUES
('What is the difference between Spring @Component, @Service, @Repository and @Controller?',
 'I know all four are used for Spring bean detection, but I am confused about when to use which one. Are they interchangeable, or does Spring treat them differently at runtime?',
 2,'APPROVED',124,'2026-01-15 10:30:00'),
('How does Spring Boot auto-configuration work internally?',
 'I want to understand what happens under the hood when Spring Boot starts. How does it decide which beans to create automatically?',
 3,'APPROVED',89,'2026-01-16 09:15:00'),
('What is the N+1 problem in Hibernate and how to fix it?',
 'My application is running too many SQL queries. I have a list of 50 orders and each one is triggering a separate query for the customer.',
 4,'APPROVED',203,'2026-01-17 14:00:00'),
('How to properly configure HikariCP connection pool in Spring Boot?',
 'My production application occasionally throws Connection is not available, request timed out after 30000ms.',
 5,'APPROVED',156,'2026-01-18 11:45:00'),
('What is the difference between @Transactional on class vs method level?',
 'If I put @Transactional on the service class and also on individual methods, which one takes precedence?',
 6,'APPROVED',178,'2026-01-19 16:20:00'),
('How to implement JWT authentication with Spring Security 6?',
 'I am upgrading from Spring Security 5 to 6 and the WebSecurityConfigurerAdapter is gone.',
 2,'PENDING',0,'2026-01-20 08:00:00'),
('What is the difference between CrudRepository, JpaRepository and PagingAndSortingRepository?',
 'Spring Data JPA provides multiple repository interfaces. When should I extend which one?',
 3,'APPROVED',67,'2026-01-21 13:30:00'),
('How does Spring Bean scope work — singleton vs prototype?',
 'By default Spring beans are singleton. What exactly does singleton mean in the context of Spring?',
 4,'APPROVED',91,'2026-01-22 10:10:00'),
('What are the best practices for exception handling in Spring Boot REST APIs?',
 'I want to standardize my error responses across all endpoints.',
 5,'APPROVED',45,'2026-01-23 15:50:00'),
('How to use Liquibase with Spring Boot for database migration?',
 'I want to manage my database schema changes using Liquibase instead of Hibernate hbm2ddl.auto=update.',
 6,'APPROVED',112,'2026-01-24 09:00:00');

INSERT INTO answers (body,author_id,question_id,status,is_accepted,created_at) VALUES
('All four annotations are functionally equivalent for component scanning. However @Repository adds exception translation, @Controller is recognized by Spring MVC DispatcherServlet, @Service has no extra behavior currently, and @Component is the generic fallback. Best practice: use the most specific annotation that matches the layer.',
 3,1,'APPROVED',1,'2026-01-15 12:00:00'),
('There is an important practical difference with @Repository. If you do not annotate your DAO with @Repository, unchecked persistence exceptions will not be translated into Spring DataAccessException.',
 6,1,'APPROVED',0,'2026-01-15 14:30:00'),
('Spring Boot auto-configuration works through @EnableAutoConfiguration reading AutoConfiguration.imports. Each entry uses @ConditionalOnClass, @ConditionalOnMissingBean etc to decide whether to activate. Run with --debug to see the auto-configuration report.',
 6,2,'APPROVED',1,'2026-01-16 11:00:00'),
('N+1 solutions: 1) JOIN FETCH in JPQL, 2) @EntityGraph, 3) batch fetching with hibernate.default_batch_fetch_size, 4) DTO projections. Use JOIN FETCH for simple parent-child, batch fetching when you need full entities, DTO projections for read-only list views.',
 5,3,'APPROVED',1,'2026-01-17 16:00:00'),
('Set maximum-pool-size=10, minimum-idle=5, connection-timeout=20000, idle-timeout=300000, max-lifetime=1200000. Also set spring.jpa.open-in-view=false — if true, connections are held open for the entire HTTP request unnecessarily.',
 2,4,'APPROVED',1,'2026-01-18 13:30:00'),
('@Transactional precedence: method-level overrides class-level. Self-invocation problem: calling another @Transactional method on the same class bypasses the proxy. Fix by injecting self with @Lazy or restructuring into two classes.',
 4,5,'APPROVED',1,'2026-01-19 18:00:00'),
('Always extend JpaRepository for regular application code — it gives you everything. CrudRepository is basic CRUD, PagingAndSortingRepository adds Pageable, JpaRepository adds flush/saveAndFlush/deleteInBatch.',
 6,7,'APPROVED',1,'2026-01-21 15:00:00'),
('Also: use getReferenceById instead of findById when you only need a proxy reference for a FK. This avoids an unnecessary SELECT query.',
 4,7,'PENDING',0,'2026-01-22 09:00:00'),
('Spring singleton = one instance per ApplicationContext, not per JVM. To inject prototype into singleton: use ObjectFactory<T> injection or @Lookup method injection.',
 3,8,'APPROVED',1,'2026-01-22 12:00:00'),
('Add liquibase-core dependency, set spring.liquibase.change-log in properties, set ddl-auto=none. Use XML format for rollback support. Never modify existing changeSets — always create new ones.',
 2,10,'APPROVED',1,'2026-01-24 11:00:00');

INSERT INTO tags (name) VALUES
('java'),('spring-boot'),('spring-security'),('hibernate'),('jpa'),
('database'),('liquibase'),('performance'),('transactions'),('rest-api'),
('spring-mvc'),('jdbc');

INSERT INTO question_tags (question_id,tag_id) VALUES
(1,1),(1,2),(1,11),
(2,2),(2,1),
(3,4),(3,5),(3,8),(3,6),
(4,6),(4,2),(4,8),
(5,1),(5,2),(5,9),
(6,3),(6,2),(6,10),
(7,5),(7,2),(7,6),
(8,1),(8,2),
(9,2),(9,10),(9,1),
(10,7),(10,2),(10,6);
