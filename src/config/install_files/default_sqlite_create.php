<?php
/**
 * @noinspection PhpUnnecessaryDoubleQuotesInspection
 */
return [
    'PRAGMA foreign_keys = off',
    'DROP TABLE IF EXISTS {dbPrefix}aliases',
    'DROP TABLE IF EXISTS {dbPrefix}blocks',
    'DROP TABLE IF EXISTS {dbPrefix}cache',
    'DROP TABLE IF EXISTS {dbPrefix}constants',
    'DROP TABLE IF EXISTS {dbPrefix}content',
    'DROP TABLE IF EXISTS {dbPrefix}groups',
    'DROP TABLE IF EXISTS {dbPrefix}nav_ng_map',
    'DROP TABLE IF EXISTS {dbPrefix}navgroups',
    'DROP TABLE IF EXISTS {dbPrefix}navigation',
    'DROP TABLE IF EXISTS {dbPrefix}page',
    'DROP TABLE IF EXISTS {dbPrefix}page_blocks_map',
    'DROP TABLE IF EXISTS {dbPrefix}people',
    'DROP TABLE IF EXISTS {dbPrefix}people_group_map',
    'DROP TABLE IF EXISTS {dbPrefix}routes',
    'DROP TABLE IF EXISTS {dbPrefix}routes_group_map',
    'DROP TABLE IF EXISTS {dbPrefix}twig_dirs',
    'DROP TABLE IF EXISTS {dbPrefix}twig_prefix',
    'DROP TABLE IF EXISTS {dbPrefix}twig_templates',
    'DROP TABLE IF EXISTS {dbPrefix}twig_themes',
    'DROP TABLE IF EXISTS {dbPrefix}urls',
    'CREATE TABLE {dbPrefix}aliases (
        a_id     INTEGER       PRIMARY KEY AUTOINCREMENT,
        a_url_id INTEGER       NOT NULL
                               REFERENCES {dbPrefix}urls (url_id) ON DELETE CASCADE
                                                            ON UPDATE CASCADE,
        a_alias  VARCHAR (150) NOT NULL
                               DEFAULT ""
    )',

    'CREATE TABLE {dbPrefix}blocks (
        b_id        INTEGER      PRIMARY KEY AUTOINCREMENT,
        b_name      VARCHAR (64) NOT NULL
                                 DEFAULT "body"
                                 UNIQUE,
        b_type      VARCHAR (10) CHECK (b_type IN ("shared", "solo") )
                                 NOT NULL
                                 DEFAULT "shared",
        b_active    VARCHAR (10) CHECK (b_active IN ("true", "false") )
                                 NOT NULL
                                 DEFAULT "true",
        b_immutable VARCHAR (10) CHECK (b_immutable IN ("true", "false") )
                                 NOT NULL
                                 DEFAULT "false"
    )',

    'CREATE TABLE {dbPrefix}cache (
        cache_id        INTEGER      PRIMARY KEY AUTOINCREMENT
                                     NOT NULL,
        cache_key       VARCHAR (250) UNIQUE
                                     NOT NULL
                                     DEFAULT "bad",
        cache_value     TEXT,
        cache_expires   INTEGER      DEFAULT (0) 
                                     NOT NULL
      )',

    'CREATE TABLE {dbPrefix}constants (
        const_id        INTEGER      PRIMARY KEY AUTOINCREMENT
                                     NOT NULL,
        const_name      VARCHAR (64) NOT NULL
                                     UNIQUE,
        const_value     VARCHAR (64) NOT NULL,
        const_immutable VARCHAR (10) NOT NULL
                                     DEFAULT "false"
                                     CHECK (const_immutable IN ("true", "false") )
    )',


    'CREATE TABLE {dbPrefix}content (
        c_id            INTEGER       PRIMARY KEY AUTOINCREMENT,
        c_pbm_id        INTEGER,
        c_content       TEXT          NOT NULL,
        c_short_content VARCHAR (250) NOT NULL
                                      DEFAULT "",
        c_type          VARCHAR (10)  CHECK (c_type IN ("text", "html", "md", "mde", "xml", "raw") )
                                      NOT NULL
                                      DEFAULT "text",
        c_created       DATETIME      NOT NULL 
                                      DEFAULT "1000-01-01 00:00:00",
        c_updated       DATETIME      NOT NULL 
                                      DEFAULT "1000-01-01 00:00:00",
        c_version       INTEGER       NOT NULL
                                      DEFAULT (1),
        c_current       VARCHAR (10)  CHECK (c_current IN ("true", "false") )
                                      NOT NULL
                                      DEFAULT "true",
        c_featured      VARCHAR (10)  CHECK (c_featured IN ("true", "false") )
                                      NOT NULL
                                      DEFAULT "false"
    )',

    'CREATE TABLE {dbPrefix}groups (
        group_id          INTEGER       PRIMARY KEY
                                        NOT NULL,
        group_name        VARCHAR (128) NOT NULL
                                        UNIQUE,
        group_description VARCHAR (128) NOT NULL,
        group_auth_level  INTEGER (11)  NOT NULL
                                        DEFAULT (0),
        group_immutable   VARCHAR (10)  NOT NULL
                                        DEFAULT "false"
                                        CHECK (group_immutable IN ("true", "false") )
    )',

    'CREATE TABLE {dbPrefix}nav_ng_map (
        nnm_id INTEGER PRIMARY KEY AUTOINCREMENT,
        ng_id  INTEGER NOT NULL
                       REFERENCES {dbPrefix}navgroups (ng_id) ON DELETE CASCADE
                                                        ON UPDATE CASCADE,
        nav_id INTEGER NOT NULL
                       REFERENCES {dbPrefix}navigation (nav_id) ON DELETE CASCADE
                                                          ON UPDATE CASCADE,
        UNIQUE (
            ng_id,
            nav_id
        )
    )',

    'CREATE TABLE {dbPrefix}navgroups (
        ng_id        INTEGER       PRIMARY KEY AUTOINCREMENT,
        ng_name      VARCHAR (128) UNIQUE
                                   NOT NULL
                                   DEFAULT "Main",
        ng_active    VARCHAR (10)  CHECK (ng_active IN ("true", "false") )
                                   NOT NULL
                                   DEFAULT "true",
        ng_default   VARCHAR (10)  CHECK (ng_default IN ("true", "false") )
                                   NOT NULL
                                   DEFAULT "false",
        ng_immutable VARCHAR (10)  CHECK (ng_immutable IN ("true", "false") )
                                   NOT NULL
                                   DEFAULT "false"
    )',

    'CREATE TABLE {dbPrefix}navigation (
        nav_id          INTEGER       PRIMARY KEY AUTOINCREMENT,
        url_id          INTEGER       NOT NULL
                                      DEFAULT (0)
                                      REFERENCES {dbPrefix}urls (url_id) ON DELETE CASCADE
                                                                   ON UPDATE CASCADE,
        parent_id       INTEGER       NOT NULL
                                      DEFAULT (0),
        nav_name        VARCHAR (128) NOT NULL
                                      DEFAULT "Fred",
        nav_text        VARCHAR (128) NOT NULL
                                      DEFAULT "",
        nav_description VARCHAR (255) NOT NULL
                                      DEFAULT "",
        nav_css         VARCHAR (64)  NOT NULL
                                      DEFAULT "",
        nav_level       INTEGER       NOT NULL
                                      DEFAULT (1),
        nav_order       INTEGER       NOT NULL
                                      DEFAULT (0),
        nav_active      VARCHAR (10)  CHECK (nav_active IN ("true", "false") )
                                      DEFAULT "true"
                                      NOT NULL,
        nav_immutable   VARCHAR (10)  CHECK (nav_immutable IN ("true", "false") )
                                      NOT NULL
                                      DEFAULT "false"
    )',

    'CREATE TABLE {dbPrefix}page (
        page_id          INTEGER       PRIMARY KEY AUTOINCREMENT
                                       NOT NULL,
        url_id           INTEGER       NOT NULL,
        ng_id            INTEGER       NOT NULL
                                       REFERENCES {dbPrefix}navgroups (ng_id) ON DELETE NO ACTION
                                                                        ON UPDATE NO ACTION,
        tpl_id           INTEGER       NOT NULL
                                       REFERENCES {dbPrefix}twig_templates (tpl_id) ON DELETE CASCADE
                                                                              ON UPDATE CASCADE,
        page_type        VARCHAR (20)  NOT NULL
                                       DEFAULT "text/html",
        page_title       VARCHAR (100) NOT NULL
                                       DEFAULT "Needs a title",
        page_description VARCHAR (150) NOT NULL
                                       DEFAULT "Needs a description",
        page_up          DATETIME      NOT NULL
                                       DEFAULT "1000-01-01 00:00:00",
        page_down        DATETIME      NOT NULL
                                       DEFAULT "9999-12-31 23:59:59",
        created_on       DATETIME      NOT NULL 
                                       DEFAULT "1000-01-01 00:00:00",
        updated_on       DATETIME      NOT NULL
                                       DEFAULT "1000-01-01 00:00:00",
        page_base_url    VARCHAR (50)  NOT NULL
                                       DEFAULT "/",
        page_lang        VARCHAR (50)  NOT NULL
                                       DEFAULT "en",
        page_charset     VARCHAR (100) NOT NULL
                                       DEFAULT "utf-8",
        page_immutable   VARCHAR (10)  NOT NULL
                                       CHECK (page_immutable IN ("true", "false") )
                                       DEFAULT "false",
        page_changefreq  VARCHAR (10)  CHECK (page_changefreq IN ("always", "hourly", "daily", "weekly", "monthly", "yearly", "never") )
                                       NOT NULL
                                       DEFAULT "yearly",
        page_priority    VARCHAR (5)   NOT NULL
                                       DEFAULT "0.5",
        FOREIGN KEY (
            url_id
        )
        REFERENCES {dbPrefix}urls (url_id) ON DELETE CASCADE
                                     ON UPDATE CASCADE
    )',

    'CREATE TABLE {dbPrefix}page_blocks_map (
        pbm_id       INTEGER PRIMARY KEY AUTOINCREMENT
                             NOT NULL,
        pbm_page_id  INTEGER NOT NULL
                             REFERENCES {dbPrefix}page (page_id) ON DELETE CASCADE
                                                           ON UPDATE CASCADE,
        pbm_block_id INTEGER NOT NULL
                             REFERENCES {dbPrefix}blocks (b_id) ON DELETE CASCADE
                                                          ON UPDATE CASCADE,
        UNIQUE (
            pbm_page_id,
            pbm_block_id
        )
    )',

    'CREATE TABLE {dbPrefix}people (
        people_id       INTEGER       PRIMARY KEY AUTOINCREMENT,
        login_id        VARCHAR (60)  UNIQUE
                                      NOT NULL
                                      DEFAULT "",
        real_name       VARCHAR (64)  NOT NULL
                                      DEFAULT "",
        short_name      VARCHAR (8)   UNIQUE
                                      NOT NULL
                                      DEFAULT "",
        password        VARCHAR (128) NOT NULL
                                      DEFAULT "",
        description     VARCHAR (250) NOT NULL
                                      DEFAULT "",
        is_logged_in    VARCHAR (10)  CHECK (is_logged_in IN ("true", "false") )
                                      NOT NULL
                                      DEFAULT "false",
        last_logged_in  DATE          NOT NULL
                                      DEFAULT "1000-01-01",
        bad_login_count INTEGER       NOT NULL
                                      DEFAULT (0),
        bad_login_ts    INTEGER       NOT NULL
                                      DEFAULT (0),
        is_active       VARCHAR (10)  CHECK (is_active IN ("true", "false") )
                                      NOT NULL
                                      DEFAULT "true",
        is_immutable    VARCHAR (10)  CHECK (is_immutable IN ("true", "false") )
                                      NOT NULL
                                      DEFAULT "false",
        created_on      DATETIME      NOT NULL DEFAULT "1000-01-01 00:00:00"
                                      
    )',

    'CREATE TABLE {dbPrefix}people_group_map (
        pgm_id    INTEGER PRIMARY KEY AUTOINCREMENT,
        people_id INTEGER REFERENCES {dbPrefix}people (people_id) ON DELETE CASCADE
                                                            ON UPDATE CASCADE
                          NOT NULL,
        group_id  INTEGER REFERENCES {dbPrefix}groups (group_id) ON DELETE CASCADE
                                                           ON UPDATE CASCADE
                          NOT NULL
    )',

    'CREATE TABLE {dbPrefix}routes (
        route_id        INTEGER       PRIMARY KEY AUTOINCREMENT
                                      NOT NULL,
        url_id          INTEGER       NOT NULL
                                      UNIQUE,
        route_class     VARCHAR (64)  NOT NULL DEFAULT "",
        route_method    VARCHAR (64)  NOT NULL DEFAULT "",
        route_action    VARCHAR (100) NOT NULL DEFAULT "",
        route_immutable VARCHAR (10)  CHECK (route_immutable IN ("true", "false") )
                                      NOT NULL
                                      DEFAULT "false",
        FOREIGN KEY (
            url_id
        )
        REFERENCES {dbPrefix}urls (url_id) ON DELETE CASCADE
                                     ON UPDATE CASCADE
    )',

    'CREATE TABLE {dbPrefix}routes_group_map (
        rgm_id   INTEGER PRIMARY KEY AUTOINCREMENT,
        route_id INTEGER NOT NULL
                         DEFAULT (0)
                         REFERENCES {dbPrefix}routes (route_id) ON DELETE CASCADE
                                                          ON UPDATE CASCADE,
        group_id INTEGER REFERENCES {dbPrefix}groups (group_id) ON DELETE CASCADE
                                                          ON UPDATE CASCADE
                         NOT NULL,
        UNIQUE (
            route_id,
            group_id
        )
    )',

    'CREATE TABLE {dbPrefix}twig_dirs (
        td_id   INTEGER      PRIMARY KEY AUTOINCREMENT
                             NOT NULL,
        tp_id   INTEGER      NOT NULL
                             REFERENCES {dbPrefix}twig_prefix (tp_id) ON DELETE CASCADE
                                                                ON UPDATE CASCADE,
        td_name VARCHAR (64) NOT NULL
                             DEFAULT "",
        UNIQUE (
            tp_id,
            td_name
        )
    )',

    'CREATE TABLE {dbPrefix}twig_prefix (
        tp_id      INTEGER       PRIMARY KEY AUTOINCREMENT,
        tp_prefix  VARCHAR (32)  NOT NULL
                                 DEFAULT "site_"
                                 UNIQUE,
        tp_path    VARCHAR (150) NOT NULL
                                 DEFAULT "/src/templates",
        tp_active  VARCHAR (10)  CHECK (tp_active IN ("true", "false") )
                                 NOT NULL
                                 DEFAULT "true",
        tp_default VARCHAR (10)  CHECK (tp_default IN ("true", "false") )
                                 NOT NULL
                                 DEFAULT "false"
    )',

    'CREATE TABLE {dbPrefix}twig_templates (
        tpl_id        INTEGER       PRIMARY KEY AUTOINCREMENT
                                    NOT NULL,
        td_id         INTEGER       NOT NULL
                                    DEFAULT (0)
                                    REFERENCES {dbPrefix}twig_dirs (td_id) ON DELETE CASCADE
                                                                     ON UPDATE CASCADE,
        theme_id      INTEGER       NOT NULL
                                    DEFAULT (0)
                                    REFERENCES {dbPrefix}twig_themes (theme_id) ON DELETE CASCADE
                                                                          ON UPDATE CASCADE,
        tpl_name      VARCHAR (128) NOT NULL
                                    DEFAULT "",
        tpl_immutable VARCHAR (10)  CHECK (tpl_immutable IN ("true", "false") )
                                    NOT NULL
                                    DEFAULT "false",
        UNIQUE (
            td_id,
            tpl_name
        )
    )',

    'CREATE TABLE {dbPrefix}twig_themes (
        theme_id      INTEGER      PRIMARY KEY AUTOINCREMENT,
        theme_name    VARCHAR (64) NOT NULL
                                   DEFAULT "base_fluid"
                                   UNIQUE,
        theme_default VARCHAR (10) CHECK (theme_default IN ("true", "false") )
                                   NOT NULL
                                   DEFAULT "false"
    )',

    'CREATE TABLE {dbPrefix}urls (
        url_id        INTEGER       PRIMARY KEY AUTOINCREMENT,
        url_host      VARCHAR (150) NOT NULL
                                    DEFAULT "self",
        url_text      VARCHAR (150) NOT NULL,
        url_scheme    VARCHAR (10)  CHECK (url_scheme IN ("http", "https", "ftp", "gopher", "mailto", "file", "ritc") )
                                    NOT NULL
                                    DEFAULT "https",
        url_immutable VARCHAR (10)  CHECK (url_immutable IN ("true", "false") )
                                    NOT NULL
                                    DEFAULT "false",
        CONSTRAINT urls_url UNIQUE (
            url_host,
            url_text,
            url_scheme
        )
        ON CONFLICT ROLLBACK
    )',
    'PRAGMA foreign_keys = on'
];
