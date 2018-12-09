<?php
return [
"DROP TABLE IF EXISTS {dbPrefix}nav_ng_map",
"DROP TABLE IF EXISTS {dbPrefix}people_group_map",
"DROP TABLE IF EXISTS {dbPrefix}routes_group_map",
"DROP TABLE IF EXISTS {dbPrefix}constants",
"DROP TABLE IF EXISTS {dbPrefix}page_blocks_map",
"DROP TABLE IF EXISTS {dbPrefix}blocks",
"DROP TABLE IF EXISTS {dbPrefix}content",
"DROP TABLE IF EXISTS {dbPrefix}groups",
"DROP TABLE IF EXISTS {dbPrefix}page",
"DROP TABLE IF EXISTS {dbPrefix}people",
"DROP TABLE IF EXISTS {dbPrefix}routes",
"DROP TABLE IF EXISTS {dbPrefix}navgroups",
"DROP TABLE IF EXISTS {dbPrefix}navigation",
"DROP TABLE IF EXISTS {dbPrefix}aliases",
"DROP TABLE IF EXISTS {dbPrefix}urls",
"DROP TABLE IF EXISTS {dbPrefix}twig_templates",
"DROP TABLE IF EXISTS {dbPrefix}twig_dirs",
"DROP TABLE IF EXISTS {dbPrefix}twig_prefix",

"DROP TYPE IF EXISTS url_protocol CASCADE",
"DROP TYPE IF EXISTS truthy CASCADE",
"DROP TYPE IF EXISTS content_type CASCADE",
"DROP TYPE IF EXISTS block_type CASCADE",
'CREATE OR REPLACE FUNCTION change_updated_on() RETURNS trigger AS
$BODY$
BEGIN
 NEW.updated_on = now();
 RETURN NEW;
END;
$BODY$ language \'plpgsql\'',
"CREATE TYPE url_protocol as ENUM ('http', 'https', 'ftp', 'gopher', 'mailto', 'file', 'ritc')",
"CREATE TYPE truthy as ENUM ('true', 'false')",
"CREATE TYPE content_type as ENUM ('text','html','md','mde','xml','raw')",
"CREATE TYPE block_type as ENUM ('shared', 'solo')",

"CREATE TABLE {dbPrefix}constants (
  const_id serial NOT NULL,
  const_name character varying(64) NOT NULL UNIQUE,
  const_value character varying(64) NOT NULL,
  const_immutable truthy NOT NULL DEFAULT 'false'::truthy,
  PRIMARY KEY (const_id)
)",

"CREATE TABLE {dbPrefix}groups (
  group_id serial NOT NULL,
  group_name character varying(40) NOT NULL UNIQUE,
  group_description character varying(128) NOT NULL,
  group_auth_level integer NOT NULL DEFAULT 0,
  group_immutable truthy NOT NULL DEFAULT 'false'::truthy,
  PRIMARY KEY (group_id)
)",

"CREATE TABLE {dbPrefix}urls (
  url_id serial NOT NULL,
  url_host character varying(255) NOT NULL DEFAULT 'self'::character varying,
  url_text character varying(255) NOT NULL DEFAULT ''::character varying,
  url_scheme url_protocol NOT NULL DEFAULT 'https'::url_protocol,
  url_immutable truthy NOT NULL DEFAULT 'false'::truthy,
  PRIMARY KEY (url_id)
)",
"CREATE UNIQUE INDEX urls_url_idx on {dbPrefix}urls USING btree (url_scheme, url_host, url_text)",

"CREATE TABLE {dbPrefix}routes (
  route_id serial NOT NULL,
  url_id integer NOT NULL UNIQUE,
  route_class character varying(64) NOT NULL,
  route_method character varying(64) NOT NULL,
  route_action character varying(255) NOT NULL,
  route_immutable truthy NOT NULL DEFAULT 'false'::truthy,
  PRIMARY KEY (route_id)
)",

"CREATE TABLE {dbPrefix}page (
  page_id serial NOT NULL,
  url_id integer NOT NULL,
  ng_id integer NOT NULL,
  tpl_id integer NOT NULL,
  page_type character varying(20) NOT NULL DEFAULT 'text/html'::character varying,
  page_title character varying(100) NOT NULL DEFAULT 'The Title'::character varying,
  page_description character varying(150) NOT NULL DEFAULT 'The Description'::character varying,
  page_up timestamp NOT NULL DEFAULT '1000-01-01 00:00:00',
  page_down timestamp NOT NULL DEFAULT '9999-12-31 23:59:59',
  created_on timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_on timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  page_base_url character varying(50) NOT NULL DEFAULT '/'::character varying,
  page_lang character varying(50) NOT NULL DEFAULT 'en'::character varying,
  page_charset character varying(100) NOT NULL DEFAULT 'utf-8'::character varying,
  page_immutable truthy NOT NULL DEFAULT 'false'::truthy,
  PRIMARY KEY (page_id)
)",
"CREATE INDEX pgm_url_id_idx on {dbPrefix}page USING btree (url_id)",
"CREATE TRIGGER page_updated_on
  BEFORE UPDATE
  ON {dbPrefix}page
  FOR EACH ROW
  EXECUTE PROCEDURE change_updated_on()",

"CREATE TABLE {dbPrefix}blocks (
  b_id serial NOT NULL,
  b_name character varying(64) NOT NULL DEFAULT 'body'::character varying,
  b_type block_type NOT NULL DEFAULT 'shared'::block_type,
  b_active truthy NOT NULL DEFAULT 'true'::truthy,
  b_immutable truthy NOT NULL DEFAULT 'false'::truthy,
  PRIMARY KEY (b_id),
  UNIQUE (b_name)
)",

"CREATE TABLE {dbPrefix}page_blocks_map (
  pbm_id serial NOT NULL,
  pbm_page_id integer NOT NULL,
  pbm_block_id integer NOT NULL,
  PRIMARY KEY (pbm_id),
  UNIQUE (pbm_page_id,pbm_block_id)
)",
"CREATE INDEX pbm_page_id_idx on {dbPrefix}page_blocks_map USING btree (pbm_page_id)",
"CREATE INDEX pbm_block_id_idx on {dbPrefix}page_blocks_map USING btree (pbm_block_id)",

"CREATE TABLE {dbPrefix}content (
  c_id serial NOT NULL,
  c_page_id integer NOT NULL,
  c_content text NOT NULL,
  c_short_content character varying(250) NOT NULL DEFAULT '',
  c_type content_type NOT NULL DEFAULT 'text'::content_type,
  c_block character varying(128) NOT NULL DEFAULT 'body',
  c_created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  c_updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  c_version integer NOT NULL DEFAULT '1',
  c_current truthy NOT NULL DEFAULT 'true'::truthy,
  c_featured truthy NOT NULL DEFAULT 'false'::truthy,
  PRIMARY KEY (c_id)
)",
"CREATE UNIQUE INDEX content_page_id_idx on {dbPrefix}content USING btree (c_page_id)",

"CREATE TABLE {dbPrefix}people (
  people_id serial NOT NULL,
  login_id character varying(60) NOT NULL,
  real_name character varying(64) NOT NULL,
  short_name character varying(8) NOT NULL,
  password character varying(128) NOT NULL,
  description character varying(250) NOT NULL DEFAULT ''::character varying,
  is_logged_in truthy NOT NULL DEFAULT 'false'::truthy,
  last_logged_in date NOT NULL DEFAULT '1000-01-01'::date,
  bad_login_count integer NOT NULL DEFAULT 0,
  bad_login_ts integer NOT NULL DEFAULT 0,
  is_active truthy NOT NULL DEFAULT 'true'::truthy,
  is_immutable truthy NOT NULL DEFAULT 'false'::truthy,
  created_on timestamp NOT NULL DEFAULT now(),
  PRIMARY KEY (people_id),
  UNIQUE (login_id),
  UNIQUE (short_name)
)",

"CREATE TABLE {dbPrefix}navgroups (
  ng_id serial NOT NULL,
  ng_name character varying(128) NOT NULL DEFAULT 'Main'::character varying,
  ng_active truthy NOT NULL DEFAULT 'true'::truthy,
  ng_default truthy NOT NULL DEFAULT 'false'::truthy,
  ng_immutable truthy NOT NULL DEFAULT 'false'::truthy,
  PRIMARY KEY (ng_id),
  UNIQUE (ng_name)
)",

"CREATE TABLE {dbPrefix}navigation (
  nav_id serial NOT NULL,
  url_id integer NOT NULL DEFAULT 0,
  parent_id integer NOT NULL DEFAULT 0,
  nav_name character varying(128) NOT NULL DEFAULT 'Fred'::character varying,
  nav_text character varying(128) NOT NULL DEFAULT ''::character varying,
  nav_description character varying(255) NOT NULL DEFAULT ''::character varying,
  nav_css character varying(64) NOT NULL DEFAULT ''::character varying,
  nav_level integer NOT NULL DEFAULT 1,
  nav_order integer NOT NULL DEFAULT 0,
  nav_active truthy NOT NULL DEFAULT 'true'::truthy,
  nav_immutable truthy NOT NULL DEFAULT 'false'::truthy,
  PRIMARY KEY (nav_id)
)",
"CREATE INDEX nav_url_id_idx on {dbPrefix}navigation USING btree (url_id)",

"CREATE TABLE {dbPrefix}nav_ng_map (
  nnm_id serial NOT NULL,
  ng_id integer NOT NULL,
  nav_id integer NOT NULL,
  PRIMARY KEY (nnm_id)
)",
"CREATE UNIQUE INDEX ng_nav_idx on {dbPrefix}nav_ng_map USING btree (ng_id, nav_id)",

"CREATE TABLE {dbPrefix}people_group_map (
  pgm_id serial NOT NULL,
  people_id integer NOT NULL,
  group_id integer NOT NULL DEFAULT '3',
  PRIMARY KEY (pgm_id)
)",
"CREATE INDEX pgm_people_id_idx on {dbPrefix}people_group_map USING btree (people_id)",
"CREATE INDEX pgm_group_id_idx on {dbPrefix}people_group_map USING btree (group_id)",
"CREATE UNIQUE INDEX people_group_idx on {dbPrefix}people_group_map USING btree (people_id, group_id)",

"CREATE TABLE {dbPrefix}routes_group_map (
  rgm_id serial NOT NULL,
  route_id integer NOT NULL DEFAULT 0,
  group_id integer NOT NULL DEFAULT 0,
  PRIMARY KEY (rgm_id)
)",
"CREATE INDEX route_id_idx on {dbPrefix}routes_group_map USING btree (route_id)",
"CREATE INDEX group_id_idx on {dbPrefix}routes_group_map USING btree (group_id)",
"CREATE UNIQUE INDEX route_group_idx on {dbPrefix}routes_group_map USING btree (route_id,group_id)",

"CREATE TABLE {dbPrefix}aliases (
  a_id serial NOT NULL,
  a_url_id integer NOT NULL DEFAULT 0,
  a_alias character varying(150) NOT NULL DEFAULT ''::character varying
)",
"CREATE INDEX a_url_id_idx on {dbPrefix}aliases USING btree (a_url_id)",

"CREATE TABLE {dbPrefix}twig_prefix (
  tp_id serial NOT NULL,
  tp_prefix character varying(32) NOT NULL,
  tp_path character varying(150) NOT NULL,
  tp_active integer DEFAULT 1 NOT NULL,
  tp_default integer DEFAULT 0 NOT NULL 
)",
"CREATE UNIQUE INDEX tp_id_idx on {dbPrefix}twig_prefix USING btree (tp_id)",
"CREATE UNIQUE INDEX tp_prefix_idx on {dbPrefix}twig_prefix USING btree (tp_prefix)",

"CREATE TABLE {dbPrefix}twig_dirs (
    td_id serial NOT NULL,
    tp_id integer NOT NULL,
    td_name character varying(64) NOT NULL
)",
"CREATE UNIQUE INDEX td_id_idx on {dbPrefix}twig_dirs USING btree (td_id)",
"CREATE UNIQUE INDEX td_combo_idx on {dbPrefix}twig_dirs USING btree (tp_id,td_name)",

"CREATE TABLE {dbPrefix}twig_templates (
    tpl_id serial NOT NULL,
    td_id integer NOT NULL,
    tpl_name character varying(128) NOT NULL,
    tpl_immutable character varying(10) NOT NULL DEFAULT 'false'::character varying 
)",
"CREATE UNIQUE INDEX tpl_id_idx on {dbPrefix}twig_templates USING btree (tpl_id)",
"CREATE UNIQUE INDEX tpl_combo_idx on {dbPrefix}twig_templates USING btree (td_id, tpl_name)",

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

"ALTER TABLE {dbPrefix}page 
    ADD CONSTRAINT {dbPrefix}page_ibfk_2 
    FOREIGN KEY (tpl_id) REFERENCES {dbPrefix}twig_templates (tpl_id) 
      ON DELETE CASCADE
      ON UPDATE CASCADE",

"ALTER TABLE {dbPrefix}page_blocks_map 
    ADD CONSTRAINT {dbPrefix}page_blocks_map_ibfk_1 
    FOREIGN KEY (pbm_page_id) REFERENCES {dbPrefix}page (page_id) 
      ON DELETE CASCADE
      ON UPDATE CASCADE",

"ALTER TABLE {dbPrefix}page_blocks_map 
    ADD CONSTRAINT {dbPrefix}page_blocks_map_ibfk_2 
    FOREIGN KEY (pbm_block_id) REFERENCES {dbPrefix}blocks (b_id) 
      ON DELETE CASCADE
      ON UPDATE CASCADE",

"ALTER TABLE {dbPrefix}content 
    ADD CONSTRAINT {dbPrefix}content_ibfk_1 
    FOREIGN KEY (c_page_id) REFERENCES {dbPrefix}page (page_id) 
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

"ALTER TABLE {dbPrefix}aliases
  ADD CONSTRAINT {dbPrefix}aliases_ibfk_1 
  FOREIGN KEY (a_url_id) REFERENCES {dbPrefix}urls (url_id) 
    ON DELETE CASCADE
    ON UPDATE CASCADE",

"ALTER TABLE {dbPrefix}twig_dirs
  ADD CONSTRAINT {dbPrefix}twig_dirs_ibfk_1 
  FOREIGN KEY (tp_id) REFERENCES {dbPrefix}twig_prefix (tp_id) 
    ON DELETE CASCADE
    ON UPDATE CASCADE",

"ALTER TABLE {dbPrefix}twig_templates
  ADD CONSTRAINT {dbPrefix}twig_templates_ibfk_1 
  FOREIGN KEY (td_id) REFERENCES {dbPrefix}twig_dirs (td_id) 
    ON DELETE CASCADE
    ON UPDATE CASCADE"
];

