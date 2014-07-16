--
-- PostgreSQL database dump
--

-- Dumped from database version 9.1.13
-- Dumped by pg_dump version 9.3.1
-- Started on 2014-07-16 19:21:30 CLT

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

DROP DATABASE traza;
--
-- TOC entry 1997 (class 1262 OID 19608)
-- Name: traza; Type: DATABASE; Schema: -; Owner: -
--

CREATE DATABASE traza WITH TEMPLATE = template0 ENCODING = 'UTF8';


\connect traza

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- TOC entry 5 (class 2615 OID 2200)
-- Name: public; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA public;


--
-- TOC entry 1998 (class 0 OID 0)
-- Dependencies: 5
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON SCHEMA public IS 'standard public schema';


--
-- TOC entry 175 (class 3079 OID 11717)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2000 (class 0 OID 0)
-- Dependencies: 175
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- TOC entry 167 (class 1259 OID 19674)
-- Name: logs_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE logs_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 161 (class 1259 OID 19609)
-- Name: logs; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE logs (
    id bigint DEFAULT nextval('logs_seq'::regclass) NOT NULL,
    rut character varying(12) NOT NULL,
    datetime timestamp without time zone NOT NULL,
    description character varying(100) NOT NULL,
    zone bigint NOT NULL,
    action character varying(3),
    sub_module bigint NOT NULL
);


--
-- TOC entry 168 (class 1259 OID 19676)
-- Name: module_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE module_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 163 (class 1259 OID 19619)
-- Name: module; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE module (
    id bigint DEFAULT nextval('module_seq'::regclass) NOT NULL,
    name character varying(20) NOT NULL,
    zone bigint NOT NULL,
    max_wait smallint DEFAULT 0 NOT NULL,
    type bigint,
    "position" character varying(25)
);


--
-- TOC entry 174 (class 1259 OID 19721)
-- Name: module_type_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE module_type_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 173 (class 1259 OID 19697)
-- Name: module_type; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE module_type (
    id bigint DEFAULT nextval('module_type_seq'::regclass) NOT NULL,
    name character varying(20) NOT NULL,
    description text,
    color character varying(7),
    shape character varying(15)
);


--
-- TOC entry 169 (class 1259 OID 19678)
-- Name: role_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE role_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 165 (class 1259 OID 19629)
-- Name: role; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE role (
    id bigint DEFAULT nextval('role_seq'::regclass) NOT NULL,
    name character varying(15) NOT NULL,
    description text
);


--
-- TOC entry 170 (class 1259 OID 19680)
-- Name: submodule_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE submodule_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 162 (class 1259 OID 19614)
-- Name: submodule; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE submodule (
    id bigint DEFAULT nextval('submodule_seq'::regclass) NOT NULL,
    name character varying(15),
    module bigint NOT NULL,
    users bigint,
    state character varying(9)
);


--
-- TOC entry 171 (class 1259 OID 19682)
-- Name: users_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE users_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 164 (class 1259 OID 19624)
-- Name: users; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE users (
    id bigint DEFAULT nextval('users_seq'::regclass) NOT NULL,
    name character varying(50) NOT NULL,
    username character varying(20) NOT NULL,
    password character varying(43) NOT NULL,
    role bigint NOT NULL,
    state character varying(10),
    color character varying(7)
);


--
-- TOC entry 172 (class 1259 OID 19684)
-- Name: zone_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE zone_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 166 (class 1259 OID 19634)
-- Name: zone; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE zone (
    id bigint DEFAULT nextval('zone_seq'::regclass) NOT NULL,
    name character varying(20) NOT NULL,
    seats smallint NOT NULL,
    description text
);


--
-- TOC entry 1871 (class 2606 OID 19613)
-- Name: logs_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY logs
    ADD CONSTRAINT logs_pkey PRIMARY KEY (id);


--
-- TOC entry 1875 (class 2606 OID 19623)
-- Name: module_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY module
    ADD CONSTRAINT module_pkey PRIMARY KEY (id);


--
-- TOC entry 1883 (class 2606 OID 19701)
-- Name: module_type_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY module_type
    ADD CONSTRAINT module_type_pkey PRIMARY KEY (id);


--
-- TOC entry 1879 (class 2606 OID 19633)
-- Name: role_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY role
    ADD CONSTRAINT role_pkey PRIMARY KEY (id);


--
-- TOC entry 1873 (class 2606 OID 19618)
-- Name: submodule_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY submodule
    ADD CONSTRAINT submodule_pkey PRIMARY KEY (id);


--
-- TOC entry 1877 (class 2606 OID 19628)
-- Name: users_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 1881 (class 2606 OID 19638)
-- Name: zone_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY zone
    ADD CONSTRAINT zone_pkey PRIMARY KEY (id);


--
-- TOC entry 1885 (class 2606 OID 19644)
-- Name: logs_sub_module_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY logs
    ADD CONSTRAINT logs_sub_module_fkey FOREIGN KEY (sub_module) REFERENCES submodule(id);


--
-- TOC entry 1886 (class 2606 OID 19649)
-- Name: logs_sub_module_fkey1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY logs
    ADD CONSTRAINT logs_sub_module_fkey1 FOREIGN KEY (sub_module) REFERENCES module(id);


--
-- TOC entry 1884 (class 2606 OID 19639)
-- Name: logs_zone_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY logs
    ADD CONSTRAINT logs_zone_fkey FOREIGN KEY (zone) REFERENCES zone(id);


--
-- TOC entry 1890 (class 2606 OID 19702)
-- Name: module_type_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY module
    ADD CONSTRAINT module_type_fkey FOREIGN KEY (type) REFERENCES module_type(id);


--
-- TOC entry 1889 (class 2606 OID 19664)
-- Name: module_zone_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY module
    ADD CONSTRAINT module_zone_fkey FOREIGN KEY (zone) REFERENCES zone(id);


--
-- TOC entry 1887 (class 2606 OID 19654)
-- Name: submodule_module_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY submodule
    ADD CONSTRAINT submodule_module_fkey FOREIGN KEY (module) REFERENCES module(id);


--
-- TOC entry 1888 (class 2606 OID 19659)
-- Name: submodule_users_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY submodule
    ADD CONSTRAINT submodule_users_fkey FOREIGN KEY (users) REFERENCES users(id);


--
-- TOC entry 1891 (class 2606 OID 19669)
-- Name: users_role_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_role_fkey FOREIGN KEY (role) REFERENCES role(id);


-- Completed on 2014-07-16 19:21:30 CLT

--
-- PostgreSQL database dump complete
--

