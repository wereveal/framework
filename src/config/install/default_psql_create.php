<?php
return [
"DROP TABLE IF EXISTS {dbPrefix}nav_ng_map",
"DROP TABLE IF EXISTS {dbPrefix}people_group_map",
"DROP TABLE IF EXISTS {dbPrefix}routes_group_map",
"DROP TABLE IF EXISTS {dbPrefix}constants",
"DROP TABLE IF EXISTS {dbPrefix}groups",
"DROP TABLE IF EXISTS {dbPrefix}page",
"DROP TABLE IF EXISTS {dbPrefix}people",
"DROP TABLE IF EXISTS {dbPrefix}routes",
"DROP TABLE IF EXISTS {dbPrefix}navgroups",
"DROP TABLE IF EXISTS {dbPrefix}navigation",
"DROP TABLE IF EXISTS {dbPrefix}urls",
"DROP TYPE IF EXISTS url_protocol CASCADE",

"CREATE TYPE url_protocol as ENUM ('http', 'https', 'ftp', 'gopher', 'mailto')",
"CREATE TABLE {dbPrefix}constants (
  const_id serial NOT NULL,
  const_name character varying(64) NOT NULL UNIQUE,
  const_value character varying(64) NOT NULL,
  const_immutable integer NOT NULL DEFAULT 0,
  PRIMARY KEY (const_id)
)",

"CREATE TABLE {dbPrefix}groups (
  group_id serial NOT NULL,
  group_name character varying(40) NOT NULL UNIQUE,
  group_description character varying(128) NOT NULL,
  group_auth_level integer NOT NULL DEFAULT 0,
  group_immutable integer NOT NULL DEFAULT 0,
  PRIMARY KEY (group_id)
)",

"CREATE TABLE {dbPrefix}urls (
  url_id serial NOT NULL,
  url_host character varying(255) NOT NULL DEFAULT 'self'::character varying,
  url_text character varying(255) NOT NULL DEFAULT ''::character varying,
  url_scheme url_protocol NOT NULL DEFAULT 'https'::url_protocol,
  url_immutable integer NOT NULL DEFAULT 0,
  PRIMARY KEY (url_id)
)",

"CREATE TABLE {dbPrefix}routes (
  route_id serial NOT NULL,
  url_id integer NOT NULL UNIQUE,
  route_class character varying(64) NOT NULL,
  route_method character varying(64) NOT NULL,
  route_action character varying(255) NOT NULL,
  route_immutable integer NOT NULL DEFAULT 0::integer,
  PRIMARY KEY (route_id)
)",

"CREATE TABLE {dbPrefix}page (
  page_id serial NOT NULL,
  url_id integer NOT NULL,
  ng_id integer NOT NULL,
  page_twig_prefix character varying(20) NOT NULL DEFAULT 'site'::character varying,
  page_tpl character varying(64) NOT NULL DEFAULT 'index'::character varying,
  page_type character varying(20) NOT NULL DEFAULT 'text/html'::character varying,
  page_title character varying(100) NOT NULL DEFAULT 'The Title'::character varying,
  page_description character varying(150) NOT NULL DEFAULT 'The Description'::character varying,
  page_base_url character varying(50) NOT NULL DEFAULT '/'::character varying,
  page_lang character varying(50) NOT NULL DEFAULT 'en'::character varying,
  page_charset character varying(100) NOT NULL DEFAULT 'utf-8'::character varying,
  page_immutable integer NOT NULL DEFAULT 0,
  PRIMARY KEY (page_id)
)",
"CREATE INDEX pgm_url_id_idx on {dbPrefix}page (url_id)",

"CREATE TABLE {dbPrefix}people (
  people_id serial NOT NULL,
  login_id character varying(60) NOT NULL,
  real_name character varying(50) NOT NULL,
  short_name character varying(8) NOT NULL,
  password character varying(128) NOT NULL,
  description character varying(250) NOT NULL DEFAULT ''::character varying,
  is_logged_in integer NOT NULL DEFAULT 0,
  bad_login_count integer NOT NULL DEFAULT 0,
  bad_login_ts integer NOT NULL DEFAULT 0,
  is_active integer NOT NULL DEFAULT 1,
  is_immutable integer NOT NULL DEFAULT 0,
  created_on timestamp NOT NULL DEFAULT now(),
  PRIMARY KEY (people_id),
  UNIQUE (login_id),
  UNIQUE (short_name)
)",

"CREATE TABLE {dbPrefix}navgroups (
  ng_id serial NOT NULL,
  ng_name character varying(128) NOT NULL DEFAULT 'Main'::character varying,
  ng_active integer NOT NULL DEFAULT 1,
  ng_default integer NOT NULL DEFAULT 0,
  PRIMARY KEY (ng_id),
  UNIQUE (ng_name)
)",

"CREATE TABLE {dbPrefix}navigation (
  nav_id serial NOT NULL,
  url_id integer NOT NULL DEFAULT 0,
  nav_parent_id integer NOT NULL DEFAULT 0,
  nav_name character varying(128) NOT NULL DEFAULT 'Fred'::character varying,
  nav_text character varying(128) NOT NULL DEFAULT ''::character varying,
  nav_description character varying(255) NOT NULL DEFAULT ''::character varying,
  nav_css character varying(64) NOT NULL DEFAULT 'menu-item'::character varying,
  nav_level integer NOT NULL DEFAULT 1,
  nav_order integer NOT NULL DEFAULT 0,
  nav_active integer NOT NULL DEFAULT 1,
  PRIMARY KEY (nav_id)
)",
"CREATE INDEX nav_url_id_idx on {dbPrefix}navigation (url_id)",

"CREATE TABLE {dbPrefix}nav_ng_map (
  nnm_id serial NOT NULL,
  ng_id integer NOT NULL,
  nav_id integer NOT NULL,
  PRIMARY KEY (nnm_id)
)",
"CREATE UNIQUE INDEX ng_nav_idx on {dbPrefix}nav_ng_map (ng_id, nav_id)",

"CREATE TABLE {dbPrefix}people_group_map (
  pgm_id serial NOT NULL,
  people_id integer NOT NULL,
  group_id integer NOT NULL DEFAULT '3',
  PRIMARY KEY (pgm_id)
)",
"CREATE INDEX pgm_people_id_idx on {dbPrefix}people_group_map (people_id)",
"CREATE INDEX pgm_group_id_idx on {dbPrefix}people_group_map (group_id)",
"CREATE UNIQUE INDEX people_group_idx on {dbPrefix}people_group_map (people_id, group_id)",

"CREATE TABLE {dbPrefix}routes_group_map (
  rgm_id serial NOT NULL,
  route_id integer NOT NULL DEFAULT 0,
  group_id integer NOT NULL DEFAULT 0,
  PRIMARY KEY (rgm_id)
)",
"CREATE INDEX route_id_idx on {dbPrefix}routes_group_map (route_id)",
"CREATE INDEX group_id_idx on {dbPrefix}routes_group_map (group_id)",
"CREATE UNIQUE INDEX route_group_idx on {dbPrefix}routes_group_map (route_id,group_id)",

"ALTER TABLE ONLY {dbPrefix}routes 
    ADD CONSTRAINT {dbPrefix}routes_ibfk_1 
    FOREIGN KEY (url_id) REFERENCES {dbPrefix}urls (url_id) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE",

"ALTER TABLE {dbPrefix}page 
    ADD CONSTRAINT {dbPrefix}page_ibfk_1 
    FOREIGN KEY (url_id) REFERENCES {dbPrefix}urls (url_id) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE",

"ALTER TABLE {dbPrefix}navigation 
    ADD CONSTRAINT {dbPrefix}navigation_ibfk_1 
    FOREIGN KEY (url_id) REFERENCES {dbPrefix}urls (url_id) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE",

"ALTER TABLE ONLY {dbPrefix}people_group_map
    ADD CONSTRAINT {dbPrefix}pgm_ibfk_1 
    FOREIGN KEY (people_id) REFERENCES {dbPrefix}people (people_id) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE",
"ALTER TABLE ONLY {dbPrefix}people_group_map
    ADD CONSTRAINT {dbPrefix}pgm_ibfk_2 
    FOREIGN KEY (group_id) REFERENCES {dbPrefix}groups (group_id) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE",

"ALTER TABLE {dbPrefix}routes_group_map
    ADD CONSTRAINT {dbPrefix}routes_group_map_ibfk_1 
    FOREIGN KEY (route_id) REFERENCES {dbPrefix}routes (route_id) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE",
"ALTER TABLE {dbPrefix}routes_group_map
    ADD CONSTRAINT {dbPrefix}routes_group_map_ibfk_2 
    FOREIGN KEY (group_id) REFERENCES {dbPrefix}groups (group_id) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE",

"ALTER TABLE {dbPrefix}nav_ng_map
  ADD CONSTRAINT {dbPrefix}nav_ng_map_ibfk_1 
  FOREIGN KEY (ng_id) REFERENCES {dbPrefix}navgroups (ng_id) 
  ON DELETE CASCADE 
  ON UPDATE CASCADE",
"ALTER TABLE {dbPrefix}nav_ng_map
  ADD CONSTRAINT {dbPrefix}nav_ng_map_ibfk_2 
  FOREIGN KEY (nav_id) REFERENCES {dbPrefix}navigation (nav_id) 
  ON DELETE CASCADE 
  ON UPDATE CASCADE",
];

