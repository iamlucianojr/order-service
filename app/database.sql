--
-- PostgreSQL database dump
--

-- Dumped from database version 11.6 (Debian 11.6-1.pgdg90+1)
-- Dumped by pg_dump version 12.1 (Debian 12.1-1.pgdg90+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

DROP INDEX public.event_streams_category_idx;
ALTER TABLE ONLY public.projections DROP CONSTRAINT projections_pkey;
ALTER TABLE ONLY public.projections DROP CONSTRAINT projections_name_key;
ALTER TABLE ONLY public.migration_versions DROP CONSTRAINT migration_versions_pkey;
ALTER TABLE ONLY public.event_streams DROP CONSTRAINT event_streams_stream_name_key;
ALTER TABLE ONLY public.event_streams DROP CONSTRAINT event_streams_pkey;
ALTER TABLE public.projections ALTER COLUMN no DROP DEFAULT;
ALTER TABLE public.event_streams ALTER COLUMN no DROP DEFAULT;
DROP SEQUENCE public.projections_no_seq;
DROP TABLE public.projections;
DROP TABLE public.migration_versions;
DROP SEQUENCE public.event_streams_no_seq;
DROP TABLE public.event_streams;
SET default_tablespace = '';

--
-- Name: event_streams; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.event_streams (
    no bigint NOT NULL,
    real_stream_name character varying(150) NOT NULL,
    stream_name character(41) NOT NULL,
    metadata jsonb,
    category character varying(150)
);


--
-- Name: event_streams_no_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.event_streams_no_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: event_streams_no_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.event_streams_no_seq OWNED BY public.event_streams.no;


--
-- Name: migration_versions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migration_versions (
    version character varying(14) NOT NULL,
    executed_at timestamp(0) without time zone NOT NULL
);


--
-- Name: COLUMN migration_versions.executed_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.migration_versions.executed_at IS '(DC2Type:datetime_immutable)';


--
-- Name: projections; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.projections (
    no bigint NOT NULL,
    name character varying(150) NOT NULL,
    "position" jsonb,
    state jsonb,
    status character varying(28) NOT NULL,
    locked_until character(26)
);


--
-- Name: projections_no_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.projections_no_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: projections_no_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.projections_no_seq OWNED BY public.projections.no;


--
-- Name: event_streams no; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.event_streams ALTER COLUMN no SET DEFAULT nextval('public.event_streams_no_seq'::regclass);


--
-- Name: projections no; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.projections ALTER COLUMN no SET DEFAULT nextval('public.projections_no_seq'::regclass);


--
-- Data for Name: event_streams; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.event_streams (no, real_stream_name, stream_name, metadata, category) FROM stdin;
\.


--
-- Data for Name: migration_versions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.migration_versions (version, executed_at) FROM stdin;
\.


--
-- Data for Name: projections; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.projections (no, name, "position", state, status, locked_until) FROM stdin;
\.


--
-- Name: event_streams_no_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.event_streams_no_seq', 1, false);


--
-- Name: projections_no_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.projections_no_seq', 1, false);


--
-- Name: event_streams event_streams_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.event_streams
    ADD CONSTRAINT event_streams_pkey PRIMARY KEY (no);


--
-- Name: event_streams event_streams_stream_name_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.event_streams
    ADD CONSTRAINT event_streams_stream_name_key UNIQUE (stream_name);


--
-- Name: migration_versions migration_versions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migration_versions
    ADD CONSTRAINT migration_versions_pkey PRIMARY KEY (version);


--
-- Name: projections projections_name_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.projections
    ADD CONSTRAINT projections_name_key UNIQUE (name);


--
-- Name: projections projections_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.projections
    ADD CONSTRAINT projections_pkey PRIMARY KEY (no);


--
-- Name: event_streams_category_idx; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX event_streams_category_idx ON public.event_streams USING btree (category);


--
-- PostgreSQL database dump complete
--

