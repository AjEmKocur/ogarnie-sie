--
-- PostgreSQL database dump
--

\restrict dDIX8xdGfktQ3ETkO4L4EWjhCDb1PoEfNsoYGVj70aunLpOgtZbXOyTS0RtZEWu

-- Dumped from database version 18.1
-- Dumped by pg_dump version 18.1

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: blog_posts; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.blog_posts (
    id bigint NOT NULL,
    title character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    excerpt text,
    content text,
    is_published boolean DEFAULT false NOT NULL,
    published_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.blog_posts OWNER TO sail;

--
-- Name: blog_posts_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.blog_posts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.blog_posts_id_seq OWNER TO sail;

--
-- Name: blog_posts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.blog_posts_id_seq OWNED BY public.blog_posts.id;


--
-- Name: cache; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache OWNER TO sail;

--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO sail;

--
-- Name: contact_messages; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.contact_messages (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    phone character varying(255),
    subject character varying(255) NOT NULL,
    message text NOT NULL,
    status character varying(255) DEFAULT 'new'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.contact_messages OWNER TO sail;

--
-- Name: contact_messages_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.contact_messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.contact_messages_id_seq OWNER TO sail;

--
-- Name: contact_messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.contact_messages_id_seq OWNED BY public.contact_messages.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO sail;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO sail;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


ALTER TABLE public.job_batches OWNER TO sail;

--
-- Name: jobs; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO sail;

--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jobs_id_seq OWNER TO sail;

--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO sail;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO sail;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: orders; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.orders (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    order_number character varying(255) NOT NULL,
    item_name character varying(255) NOT NULL,
    quantity integer DEFAULT 1 NOT NULL,
    total_price numeric(10,2),
    details text,
    status character varying(255) DEFAULT 'new'::character varying NOT NULL,
    admin_note text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.orders OWNER TO sail;

--
-- Name: orders_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.orders_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.orders_id_seq OWNER TO sail;

--
-- Name: orders_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.orders_id_seq OWNED BY public.orders.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO sail;

--
-- Name: pricing_items; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.pricing_items (
    id bigint NOT NULL,
    service_name character varying(255) NOT NULL,
    price_from numeric(10,2) NOT NULL,
    turnaround_time character varying(255),
    notes text,
    is_active boolean DEFAULT true NOT NULL,
    sort_order integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    service_id bigint
);


ALTER TABLE public.pricing_items OWNER TO sail;

--
-- Name: pricing_items_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.pricing_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.pricing_items_id_seq OWNER TO sail;

--
-- Name: pricing_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.pricing_items_id_seq OWNED BY public.pricing_items.id;


--
-- Name: service_ticket; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.service_ticket (
    id bigint NOT NULL,
    ticket_id bigint NOT NULL,
    service_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.service_ticket OWNER TO sail;

--
-- Name: service_ticket_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.service_ticket_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.service_ticket_id_seq OWNER TO sail;

--
-- Name: service_ticket_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.service_ticket_id_seq OWNED BY public.service_ticket.id;


--
-- Name: services; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.services (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    price_from numeric(10,2),
    is_active boolean DEFAULT true NOT NULL,
    sort_order integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    long_description text
);


ALTER TABLE public.services OWNER TO sail;

--
-- Name: services_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.services_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.services_id_seq OWNER TO sail;

--
-- Name: services_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.services_id_seq OWNED BY public.services.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO sail;

--
-- Name: testimonials; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.testimonials (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    ticket_id bigint NOT NULL,
    rating smallint NOT NULL,
    content text NOT NULL,
    is_approved boolean DEFAULT false NOT NULL,
    approved_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.testimonials OWNER TO sail;

--
-- Name: testimonials_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.testimonials_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.testimonials_id_seq OWNER TO sail;

--
-- Name: testimonials_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.testimonials_id_seq OWNED BY public.testimonials.id;


--
-- Name: ticket_attachments; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.ticket_attachments (
    id bigint NOT NULL,
    ticket_id bigint NOT NULL,
    user_id bigint NOT NULL,
    disk character varying(255) DEFAULT 'local'::character varying NOT NULL,
    path character varying(255) NOT NULL,
    original_name character varying(255) NOT NULL,
    mime_type character varying(255),
    size bigint DEFAULT '0'::bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.ticket_attachments OWNER TO sail;

--
-- Name: ticket_attachments_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.ticket_attachments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ticket_attachments_id_seq OWNER TO sail;

--
-- Name: ticket_attachments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.ticket_attachments_id_seq OWNED BY public.ticket_attachments.id;


--
-- Name: ticket_messages; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.ticket_messages (
    id bigint NOT NULL,
    ticket_id bigint NOT NULL,
    user_id bigint NOT NULL,
    message text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.ticket_messages OWNER TO sail;

--
-- Name: ticket_messages_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.ticket_messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ticket_messages_id_seq OWNER TO sail;

--
-- Name: ticket_messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.ticket_messages_id_seq OWNED BY public.ticket_messages.id;


--
-- Name: ticket_status_histories; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.ticket_status_histories (
    id bigint NOT NULL,
    ticket_id bigint NOT NULL,
    changed_by_user_id bigint,
    status character varying(50) NOT NULL,
    admin_note text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.ticket_status_histories OWNER TO sail;

--
-- Name: ticket_status_histories_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.ticket_status_histories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ticket_status_histories_id_seq OWNER TO sail;

--
-- Name: ticket_status_histories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.ticket_status_histories_id_seq OWNED BY public.ticket_status_histories.id;


--
-- Name: tickets; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.tickets (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    title character varying(255) NOT NULL,
    description text NOT NULL,
    status character varying(255) DEFAULT 'new'::character varying NOT NULL,
    admin_note text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    custom_request text,
    estimated_price_from numeric(10,2),
    payment_mode character varying(255) DEFAULT 'none'::character varying NOT NULL,
    payment_amount numeric(10,2),
    payment_status character varying(255) DEFAULT 'not_required'::character varying NOT NULL,
    payment_note text,
    payment_requested_at timestamp(0) without time zone,
    paid_at timestamp(0) without time zone
);


ALTER TABLE public.tickets OWNER TO sail;

--
-- Name: tickets_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.tickets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tickets_id_seq OWNER TO sail;

--
-- Name: tickets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.tickets_id_seq OWNED BY public.tickets.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: sail
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    role character varying(255) DEFAULT 'client'::character varying NOT NULL
);


ALTER TABLE public.users OWNER TO sail;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: sail
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO sail;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sail
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: blog_posts id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.blog_posts ALTER COLUMN id SET DEFAULT nextval('public.blog_posts_id_seq'::regclass);


--
-- Name: contact_messages id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.contact_messages ALTER COLUMN id SET DEFAULT nextval('public.contact_messages_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: orders id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.orders ALTER COLUMN id SET DEFAULT nextval('public.orders_id_seq'::regclass);


--
-- Name: pricing_items id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.pricing_items ALTER COLUMN id SET DEFAULT nextval('public.pricing_items_id_seq'::regclass);


--
-- Name: service_ticket id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.service_ticket ALTER COLUMN id SET DEFAULT nextval('public.service_ticket_id_seq'::regclass);


--
-- Name: services id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.services ALTER COLUMN id SET DEFAULT nextval('public.services_id_seq'::regclass);


--
-- Name: testimonials id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.testimonials ALTER COLUMN id SET DEFAULT nextval('public.testimonials_id_seq'::regclass);


--
-- Name: ticket_attachments id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.ticket_attachments ALTER COLUMN id SET DEFAULT nextval('public.ticket_attachments_id_seq'::regclass);


--
-- Name: ticket_messages id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.ticket_messages ALTER COLUMN id SET DEFAULT nextval('public.ticket_messages_id_seq'::regclass);


--
-- Name: ticket_status_histories id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.ticket_status_histories ALTER COLUMN id SET DEFAULT nextval('public.ticket_status_histories_id_seq'::regclass);


--
-- Name: tickets id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.tickets ALTER COLUMN id SET DEFAULT nextval('public.tickets_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: blog_posts; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.blog_posts (id, title, slug, excerpt, content, is_published, published_at, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.cache (key, value, expiration) FROM stdin;
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: contact_messages; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.contact_messages (id, name, email, phone, subject, message, status, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2026_02_09_195636_add_role_to_users_table	2
5	2026_02_09_203000_create_tickets_table	3
6	2026_02_09_210000_create_ticket_attachments_table	4
7	2026_02_09_220000_create_contact_messages_table	5
8	2026_02_09_223000_create_services_table	6
9	2026_02_09_223100_create_pricing_items_table	6
10	2026_02_09_223200_create_blog_posts_table	6
11	2026_02_10_000100_add_service_id_to_pricing_items_table	7
12	2026_02_19_210000_create_orders_table	8
13	2026_02_20_000100_add_service_fields_to_tickets_table	9
14	2026_02_20_000200_create_service_ticket_table	9
15	2026_02_20_010000_add_long_description_to_services_table	10
16	2026_03_03_210000_create_testimonials_table	11
17	2026_03_03_220000_create_ticket_status_histories_table	12
18	2026_03_03_223000_create_ticket_messages_table	13
19	2026_03_03_230500_add_payment_fields_to_tickets_table	14
\.


--
-- Data for Name: orders; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.orders (id, user_id, order_number, item_name, quantity, total_price, details, status, admin_note, created_at, updated_at) FROM stdin;
1	3	ORD-2026-0001	komputer	1	3000.00	komputer do gier	processing	Zamówienie przedluzy się z racji opznienia dostawy sprzętu	2026-02-19 13:07:43	2026-02-19 13:15:49
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: pricing_items; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.pricing_items (id, service_name, price_from, turnaround_time, notes, is_active, sort_order, created_at, updated_at, service_id) FROM stdin;
\.


--
-- Data for Name: service_ticket; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.service_ticket (id, ticket_id, service_id, created_at, updated_at) FROM stdin;
1	3	2	2026-02-19 15:03:34	2026-02-19 15:03:34
2	4	13	2026-02-19 19:54:07	2026-02-19 19:54:07
\.


--
-- Data for Name: services; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.services (id, name, description, price_from, is_active, sort_order, created_at, updated_at, long_description) FROM stdin;
2	Diagnostyka sprzętu	Wykrycie przyczyny awarii i propozycja naprawy.	99.99	t	10	2026-02-19 14:35:09	2026-02-19 16:24:19	Kompleksowo sprawdzamy komputer lub laptop, aby ustalić realną przyczynę problemu. Wykonujemy testy podzespołów (dysk, RAM, temperatura, zasilanie), analizę błędów systemowych oraz kontrolę stabilności pracy. Po diagnostyce klient otrzymuje jasną informację: co jest uszkodzone, co można naprawić i jaki będzie orientacyjny koszt oraz czas realizacji.
1	Czyszczenie komputera	Kompleksowe czyszczenie komputera	49.99	t	20	2026-02-09 23:25:48	2026-02-19 16:24:19	Usługa obejmuje dokładne czyszczenie wnętrza komputera lub laptopa z kurzu i zabrudzeń wpływających na temperatury oraz kulturę pracy. W ramach usługi czyścimy układ chłodzenia, wentylatory i obudowę, a także sprawdzamy stan termopadów i pasty termoprzewodzącej. Efektem jest niższa temperatura pracy, cichsza praca sprzętu i mniejsze ryzyko przegrzewania.
3	Wymiana dysku SSD/HDD	Montaż nowego dysku + konfiguracja.	69.99	t	30	2026-02-19 14:36:34	2026-02-19 16:24:19	Demontujemy stary nośnik i montujemy nowy dysk SSD/HDD dopasowany do urządzenia oraz potrzeb klienta. Konfigurujemy BIOS/UEFI, sprawdzamy kompatybilność i przygotowujemy sprzęt do dalszej pracy. Na życzenie możemy od razu przenieść system i dane, aby komputer był gotowy do użycia po odbiorze.
4	Klonowanie i migracja danych	Przeniesienie systemu i plików na nowy dysk/komputer.	179.99	t	40	2026-02-19 14:37:22	2026-02-19 16:24:19	Przenosimy system operacyjny, pliki użytkownika i podstawowe ustawienia na nowy nośnik lub nowy komputer. Usługa minimalizuje przestój i pozwala zachować dotychczasowe środowisko pracy. Po migracji weryfikujemy integralność danych oraz poprawne uruchamianie systemu.
5	Rozbudowa RAM	Dobór i montaż pamięci RAM.	79.99	t	50	2026-02-19 14:37:58	2026-02-19 16:24:19	Dobieramy i montujemy pamięć RAM zgodną z płytą główną oraz procesorem. Sprawdzamy częstotliwości, opóźnienia i tryb pracy modułów, aby uzyskać stabilne działanie komputera. Po rozbudowie wykonujemy test pamięci i potwierdzamy poprawę wydajności w codziennym użytkowaniu.
6	Instalacja systemu Windows	Czysta instalacja systemu + podstawowa konfiguracja.	199.99	t	60	2026-02-19 14:43:59	2026-02-19 16:24:19	Wykonujemy czystą instalację systemu Windows z podstawową konfiguracją pod użytkownika. Instalujemy najważniejsze sterowniki, aktualizacje bezpieczeństwa i konfigurujemy podstawowe ustawienia prywatności. Na koniec system jest gotowy do pracy i zabezpieczony aktualnym oprogramowaniem.
7	Instalacja sterowników i aktualizacji	Pełna aktualizacja i konfiguracja systemu.	79.99	t	70	2026-02-19 14:44:26	2026-02-19 16:24:19	Aktualizujemy sterowniki urządzeń oraz komponenty systemowe, aby poprawić stabilność i kompatybilność sprzętu. Rozwiązujemy problemy z niedziałającym Wi‑Fi, dźwiękiem, grafiką lub urządzeniami peryferyjnymi. Po zakończeniu usługi system działa płynniej i bez typowych konfliktów sterowników.
8	Usuwanie wirusów i malware	Skanowanie, czyszczenie i zabezpieczenie systemu.	169.99	t	80	2026-02-19 14:44:52	2026-02-19 16:24:19	Przeprowadzamy skanowanie i oczyszczanie systemu z wirusów, trojanów, adware oraz niechcianych dodatków. Usuwamy zagrożenia, przywracamy prawidłowe ustawienia systemu i przeglądarek oraz zabezpieczamy komputer przed ponowną infekcją. Po usłudze klient otrzymuje zalecenia dotyczące bezpiecznego korzystania ze sprzętu.
9	Odzyskiwanie danych (podstawowe)	Próba odzysku danych po błędach systemu/nośnika.	199.99	t	90	2026-02-19 14:45:19	2026-02-19 16:24:19	Podejmujemy próbę odzyskania utraconych plików po przypadkowym usunięciu, błędach systemu lub uszkodzeniach logicznych nośnika. Najpierw oceniamy możliwość odzysku i ryzyko dalszej pracy na dysku. Zakres obejmuje odzysk podstawowy bez ingerencji laboratoryjnej w fizycznie uszkodzone nośniki.
10	Naprawa gniazda zasilania	Diagnoza i naprawa układu zasilania laptopa.	249.99	t	100	2026-02-19 14:45:53	2026-02-19 16:24:19	Diagnozujemy problemy z ładowaniem i zasilaniem laptopa wynikające z uszkodzonego gniazda DC. Wykonujemy naprawę lub wymianę gniazda oraz kontrolę sekcji zasilania na płycie głównej. Po usłudze testujemy stabilność ładowania i poprawność pracy urządzenia pod obciążeniem.
11	Wymiana matrycy laptopa	Dobór i montaż nowej matrycy.	349.99	t	110	2026-02-19 14:46:30	2026-02-19 16:24:19	Wymieniamy uszkodzoną matrycę (pęknięcia, pasy, martwe piksele, brak podświetlenia) na kompatybilny model. Sprawdzamy rodzaj złącza, rozdzielczość i parametry ekranu przed montażem. Po wymianie wykonujemy test obrazu, kolorów i podświetlenia, aby potwierdzić pełną sprawność.
12	Usługa niestandardowa	Nietypowe zlecenie wyceniane indywidualnie.	0.00	t	120	2026-02-19 14:46:57	2026-02-19 16:24:19	Ta pozycja dotyczy nietypowych problemów, które nie mieszczą się w standardowym cenniku. Klient opisuje objawy lub cel, a my przygotowujemy indywidualną diagnozę i wycenę. Dzięki temu można zlecić zarówno pojedyncze, jak i złożone prace serwisowe w jednym zgłoszeniu.
13	Składanie komputera na zamówienie	Dobór części przez specjalistę (lub gotowych części od klienta) i montaż zestawu, konfiguracja BIOS i testy stabilności.	149.99	t	9	2026-02-19 16:29:11	2026-02-19 16:29:11	Usługa obejmuje pełne złożenie komputera stacjonarnego pod budżet i potrzeby klienta (gry, praca, nauka, grafika). Pomagamy dobrać kompatybilne podzespoły, składamy zestaw, porządkujemy okablowanie, konfigurujemy BIOS/UEFI oraz wykonujemy testy temperatur i stabilności. Na życzenie instalujemy system, sterowniki i podstawowe aplikacje. Klient otrzymuje gotowy do pracy komputer oraz zalecenia dotyczące dalszej rozbudowy.
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
hUz7qCxTR7HPFYUPq6TABse9ZW28Ng8A42chzEWm	\N	172.19.0.1	Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Mobile Safari/537.36	YTozOntzOjY6Il90b2tlbiI7czo0MDoid3NDck14RlF6WnpIWllqNlBYb3hNSUJ4S3NmMXlNTFVsbklSVXZlTCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly8xOTIuMTY4LjAuMTA1IjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fX0=	1773782250
ZJBeW5EJ2a44baS2OCp17nKNMAeiyskjq2K3El0I	2	172.19.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36	YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWUd4YnZCU2JhY3R2ZExwMkhJSU12THZmZUtndTVZYkNCRXppNURlRSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3QvYWRtaW4vdGlja2V0cyI7czo1OiJyb3V0ZSI7czoxOToiYWRtaW4udGlja2V0cy5pbmRleCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==	1773782679
0BsFd4RqmaK2uzMnjXyneut1Dky5BUYDk5nOlojK	\N	172.19.0.1	Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36	YTozOntzOjY6Il90b2tlbiI7czo0MDoiYXRDbjRmRlBva1RLb0tRWlpJUW95N01CVndLdEdBSER4UThETU82cyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xOTIuMTY4LjAuMTA1L2ZvcmdvdC1wYXNzd29yZCI7czo1OiJyb3V0ZSI7czoxNjoicGFzc3dvcmQucmVxdWVzdCI7fX0=	1773781214
HIlxu7nXMzXQDhxthyk0axW3ywpKg8e9lQ7oKDjj	2	172.19.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36	YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNHd5a3R2dGMxeWF6QjBsaEJtd1BrNEwzWFdkNEZ5eTB3UHJ6ZmZWdyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3QvYWRtaW4vdGlja2V0cyI7czo1OiJyb3V0ZSI7czoxOToiYWRtaW4udGlja2V0cy5pbmRleCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==	1774110211
rkLHRGB6wAVXTnJlrOxOOesIcGjJfHnvCH95aDdf	5	172.19.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36	YTo0OntzOjY6Il90b2tlbiI7czo0MDoibWE4Z0l4R25TRE43a0FxMkozcm90UllPMzl0R0FINlVObGxLS3R2QSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly9sb2NhbGhvc3QvY2xpZW50L3RpY2tldHMvMTAiO3M6NToicm91dGUiO3M6MTk6ImNsaWVudC50aWNrZXRzLnNob3ciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo1O30=	1774110299
\.


--
-- Data for Name: testimonials; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.testimonials (id, user_id, ticket_id, rating, content, is_approved, approved_at, created_at, updated_at) FROM stdin;
1	3	5	4	Dobrze, super obsluga i szybka	t	2026-03-12 18:01:21	2026-03-12 18:00:38	2026-03-12 18:01:21
2	3	4	4	super robota, prosto szybko i na temat.	f	\N	2026-03-12 19:20:35	2026-03-12 19:20:35
3	3	3	4	swiertna fachowa robota	f	\N	2026-03-12 19:40:22	2026-03-12 19:40:22
\.


--
-- Data for Name: ticket_attachments; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.ticket_attachments (id, ticket_id, user_id, disk, path, original_name, mime_type, size, created_at, updated_at) FROM stdin;
1	1	3	local	ticket-attachments/1/xV4H8kcf5ss6nNIkguExp8wLf25vQbCUzd6OuKUk.png	pngtree-cracked-laptop-screen-broken-display-damaged-computer-png-image_15970819.png	image/png	282158	2026-02-09 20:58:35	2026-02-09 20:58:35
\.


--
-- Data for Name: ticket_messages; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.ticket_messages (id, ticket_id, user_id, message, created_at, updated_at) FROM stdin;
1	4	2	Dzień dobry, tak jak najbardziej uda się nam złożyć komputer, mamy części na stanie. Jest Pan zainteresowany ?	2026-03-03 20:47:44	2026-03-03 20:47:44
2	4	3	Tak, pewnie.	2026-03-03 20:48:02	2026-03-03 20:48:02
3	5	3	Laptop HP, umarł i nie wstaje	2026-03-03 22:43:03	2026-03-03 22:43:03
4	5	2	Prosimy przytrzymać przycisk zailania co najmniej 20 sec i ponowną probę uruchomienia laptopa oraz prosimy o informację zwrotną czy dziala.	2026-03-03 22:44:24	2026-03-03 22:44:24
5	2	3	Witam, komputer nie uruchamia się i piszczy przy uruchamianiu. Mogę liczyć na pomoc ?	2026-03-12 20:50:42	2026-03-12 20:50:42
6	2	2	Witam, pewnie. prawdopdobnie jest to uszkodzona pamięć RAM, musimy zweryfikować komputer na miejscu. Zapraszamy :)	2026-03-12 20:56:49	2026-03-12 20:56:49
7	2	2	Super, podejdę jutro o 8:00.	2026-03-12 21:30:12	2026-03-12 21:30:12
8	2	3	podjede o 8:00	2026-03-12 21:30:38	2026-03-12 21:30:38
9	2	2	okej.	2026-03-12 21:30:50	2026-03-12 21:30:50
\.


--
-- Data for Name: ticket_status_histories; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.ticket_status_histories (id, ticket_id, changed_by_user_id, status, admin_note, created_at, updated_at) FROM stdin;
1	4	2	waiting_parts	Dzień dobry, części zostały zamówione, czekamy na ich dostarczenie.	2026-03-03 20:25:18	2026-03-03 20:25:18
2	4	2	in_progress	Dzień dobry, jesteśmy w trakcie skladania pańskiego zamówienia.	2026-03-03 20:26:24	2026-03-03 20:26:24
4	5	2	in_progress	Dzień dobry, czy możemy poprosić bardziej szczegolowe informacje ?	2026-03-03 22:42:24	2026-03-03 22:42:24
5	5	2	closed	Dzień dobry, czy możemy poprosić bardziej szczegolowe informacje ?	2026-03-12 17:57:55	2026-03-12 17:57:55
6	4	2	closed	Dzień dobry, jesteśmy w trakcie skladania pańskiego zamówienia.	2026-03-12 18:54:38	2026-03-12 18:54:38
7	3	2	closed	\N	2026-03-12 19:20:50	2026-03-12 19:20:50
8	2	2	in_progress	\N	2026-03-12 20:46:48	2026-03-12 20:46:48
9	2	3	cancelled	Zgłoszenie anulowane przez klienta.	2026-03-12 22:46:45	2026-03-12 22:46:45
10	2	2	closed	\N	2026-03-12 22:59:01	2026-03-12 22:59:01
11	2	2	in_progress	\N	2026-03-12 22:59:23	2026-03-12 22:59:23
12	2	3	cancelled	Zgłoszenie anulowane przez klienta.	2026-03-12 22:59:37	2026-03-12 22:59:37
17	5	2	in_progress	Dzień dobry, czy możemy poprosić bardziej szczegolowe informacje ?	2026-03-17 21:24:34	2026-03-17 21:24:34
50	10	5	new	\N	2026-03-21 16:21:10	2026-03-21 16:21:10
51	10	2	waiting_parts	asdasdasdasd	2026-03-21 16:23:26	2026-03-21 16:23:26
\.


--
-- Data for Name: tickets; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.tickets (id, user_id, title, description, status, admin_note, created_at, updated_at, custom_request, estimated_price_from, payment_mode, payment_amount, payment_status, payment_note, payment_requested_at, paid_at) FROM stdin;
1	3	Zepsuty komputer	Zepsuty komputer	new	\N	2026-02-09 20:47:25	2026-02-09 20:47:25	\N	\N	none	\N	not_required	\N	\N	\N
4	3	Skladanie komputer do 3000zl	Chcialbym zlozyc komputer do gier typou fortnite do 300zl. Uda sie cos zalatawic ? :)	closed	Dzień dobry, jesteśmy w trakcie skladania pańskiego zamówienia.	2026-02-19 19:54:07	2026-03-12 18:54:38	\N	149.99	none	\N	not_required	\N	\N	\N
3	3	zepsuty komputer, nie wlacza sie	Nie wlacza mi sie komputer	closed	\N	2026-02-19 15:03:34	2026-03-12 19:20:50	\N	99.99	none	\N	not_required	\N	\N	\N
2	3	test 3	test 3	cancelled	\N	2026-02-09 21:05:07	2026-03-12 22:59:37	\N	\N	on_pickup	50.00	pending	Płatność jest wymagana do realizacji usługi.	2026-03-12 20:14:41	\N
5	3	Laptop nie laptopuje	Laptop mi się nie włącza	in_progress	Dzień dobry, czy możemy poprosić bardziej szczegolowe informacje ?	2026-03-03 22:40:42	2026-03-17 21:24:34	Termin jak najszybciej	\N	none	\N	not_required	\N	\N	\N
10	5	laptop nie laptopuje	asdasdasdasdffa	waiting_parts	asdasdasdasd	2026-03-21 16:21:10	2026-03-21 16:23:26	asdasd	\N	none	\N	not_required	\N	\N	\N
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, role) FROM stdin;
1	Test User	test@example.com	2026-02-09 20:14:56	$2y$12$ZAJPHdcw.Jzy8FdVrNCSDeSdV/XRiTRVbVEpfD0LziI8mouB7/pgC	7acfzx4coQ	2026-02-09 20:14:56	2026-02-09 20:14:56	client
3	Dominik Klient	klient@serwis.local	\N	$2y$12$ruRRDaHz.aX5qf8MiRy1Ce57NZ/4suNmShet81dI5LdZF.JbfqB4S	\N	2026-02-09 20:29:48	2026-02-09 20:29:48	client
5	Dominik Kocur	dominikkocur12@gmail.com	2026-03-16 20:10:32	$2y$12$x8urPSQZsXzN2W7s.JsV1./yLD7ywKeKy9F/QEQ5TCtR6MCn5l0K6	\N	2026-03-16 20:10:16	2026-03-16 20:10:32	client
2	Administrator	admin@serwis.local	2026-03-17 19:55:36	$2y$12$Q60fodKLgIGLqXKNnBvlv.SLoa6H6yEi8lMdS.LBVU9/Z.VnjRE0S	\N	2026-02-09 20:14:56	2026-03-17 19:55:37	admin
\.


--
-- Name: blog_posts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.blog_posts_id_seq', 1, false);


--
-- Name: contact_messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.contact_messages_id_seq', 1, false);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.migrations_id_seq', 19, true);


--
-- Name: orders_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.orders_id_seq', 1, true);


--
-- Name: pricing_items_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.pricing_items_id_seq', 1, false);


--
-- Name: service_ticket_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.service_ticket_id_seq', 2, true);


--
-- Name: services_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.services_id_seq', 13, true);


--
-- Name: testimonials_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.testimonials_id_seq', 3, true);


--
-- Name: ticket_attachments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.ticket_attachments_id_seq', 3, true);


--
-- Name: ticket_messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.ticket_messages_id_seq', 11, true);


--
-- Name: ticket_status_histories_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.ticket_status_histories_id_seq', 51, true);


--
-- Name: tickets_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.tickets_id_seq', 10, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.users_id_seq', 6, true);


--
-- Name: blog_posts blog_posts_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.blog_posts
    ADD CONSTRAINT blog_posts_pkey PRIMARY KEY (id);


--
-- Name: blog_posts blog_posts_slug_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.blog_posts
    ADD CONSTRAINT blog_posts_slug_unique UNIQUE (slug);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: contact_messages contact_messages_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.contact_messages
    ADD CONSTRAINT contact_messages_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: orders orders_order_number_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.orders
    ADD CONSTRAINT orders_order_number_unique UNIQUE (order_number);


--
-- Name: orders orders_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.orders
    ADD CONSTRAINT orders_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: pricing_items pricing_items_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.pricing_items
    ADD CONSTRAINT pricing_items_pkey PRIMARY KEY (id);


--
-- Name: service_ticket service_ticket_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.service_ticket
    ADD CONSTRAINT service_ticket_pkey PRIMARY KEY (id);


--
-- Name: service_ticket service_ticket_ticket_id_service_id_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.service_ticket
    ADD CONSTRAINT service_ticket_ticket_id_service_id_unique UNIQUE (ticket_id, service_id);


--
-- Name: services services_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.services
    ADD CONSTRAINT services_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: testimonials testimonials_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.testimonials
    ADD CONSTRAINT testimonials_pkey PRIMARY KEY (id);


--
-- Name: testimonials testimonials_ticket_id_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.testimonials
    ADD CONSTRAINT testimonials_ticket_id_unique UNIQUE (ticket_id);


--
-- Name: ticket_attachments ticket_attachments_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.ticket_attachments
    ADD CONSTRAINT ticket_attachments_pkey PRIMARY KEY (id);


--
-- Name: ticket_messages ticket_messages_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.ticket_messages
    ADD CONSTRAINT ticket_messages_pkey PRIMARY KEY (id);


--
-- Name: ticket_status_histories ticket_status_histories_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.ticket_status_histories
    ADD CONSTRAINT ticket_status_histories_pkey PRIMARY KEY (id);


--
-- Name: tickets tickets_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.tickets
    ADD CONSTRAINT tickets_pkey PRIMARY KEY (id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: cache_expiration_index; Type: INDEX; Schema: public; Owner: sail
--

CREATE INDEX cache_expiration_index ON public.cache USING btree (expiration);


--
-- Name: cache_locks_expiration_index; Type: INDEX; Schema: public; Owner: sail
--

CREATE INDEX cache_locks_expiration_index ON public.cache_locks USING btree (expiration);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: sail
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: sail
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: sail
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: ticket_messages_ticket_id_created_at_index; Type: INDEX; Schema: public; Owner: sail
--

CREATE INDEX ticket_messages_ticket_id_created_at_index ON public.ticket_messages USING btree (ticket_id, created_at);


--
-- Name: orders orders_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.orders
    ADD CONSTRAINT orders_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: pricing_items pricing_items_service_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.pricing_items
    ADD CONSTRAINT pricing_items_service_id_foreign FOREIGN KEY (service_id) REFERENCES public.services(id) ON DELETE SET NULL;


--
-- Name: service_ticket service_ticket_service_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.service_ticket
    ADD CONSTRAINT service_ticket_service_id_foreign FOREIGN KEY (service_id) REFERENCES public.services(id) ON DELETE CASCADE;


--
-- Name: service_ticket service_ticket_ticket_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.service_ticket
    ADD CONSTRAINT service_ticket_ticket_id_foreign FOREIGN KEY (ticket_id) REFERENCES public.tickets(id) ON DELETE CASCADE;


--
-- Name: testimonials testimonials_ticket_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.testimonials
    ADD CONSTRAINT testimonials_ticket_id_foreign FOREIGN KEY (ticket_id) REFERENCES public.tickets(id) ON DELETE CASCADE;


--
-- Name: testimonials testimonials_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.testimonials
    ADD CONSTRAINT testimonials_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: ticket_attachments ticket_attachments_ticket_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.ticket_attachments
    ADD CONSTRAINT ticket_attachments_ticket_id_foreign FOREIGN KEY (ticket_id) REFERENCES public.tickets(id) ON DELETE CASCADE;


--
-- Name: ticket_attachments ticket_attachments_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.ticket_attachments
    ADD CONSTRAINT ticket_attachments_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: ticket_messages ticket_messages_ticket_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.ticket_messages
    ADD CONSTRAINT ticket_messages_ticket_id_foreign FOREIGN KEY (ticket_id) REFERENCES public.tickets(id) ON DELETE CASCADE;


--
-- Name: ticket_messages ticket_messages_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.ticket_messages
    ADD CONSTRAINT ticket_messages_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: ticket_status_histories ticket_status_histories_changed_by_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.ticket_status_histories
    ADD CONSTRAINT ticket_status_histories_changed_by_user_id_foreign FOREIGN KEY (changed_by_user_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: ticket_status_histories ticket_status_histories_ticket_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.ticket_status_histories
    ADD CONSTRAINT ticket_status_histories_ticket_id_foreign FOREIGN KEY (ticket_id) REFERENCES public.tickets(id) ON DELETE CASCADE;


--
-- Name: tickets tickets_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: sail
--

ALTER TABLE ONLY public.tickets
    ADD CONSTRAINT tickets_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

\unrestrict dDIX8xdGfktQ3ETkO4L4EWjhCDb1PoEfNsoYGVj70aunLpOgtZbXOyTS0RtZEWu

