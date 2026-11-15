--
-- PostgreSQL database dump
--

\restrict pGsx7jz8RKgxp3TIMf8cosJrC4fJtt0WbcliEzDctTwNFfmGaD70CEaCwNWhxx5

-- Dumped from database version 18.6
-- Dumped by pg_dump version 18.6

-- Started on 2026-11-15 16:32:05

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
-- TOC entry 225 (class 1259 OID 17770)
-- Name: detil_pembelian; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.detil_pembelian (
    no_pembelian character varying(15) NOT NULL,
    no_urut integer NOT NULL,
    kode_produk character varying(10) NOT NULL,
    jumlah integer NOT NULL,
    harga numeric(12,2) NOT NULL,
    subtotal numeric(12,2) NOT NULL,
    CONSTRAINT detil_pembelian_harga_check CHECK ((harga >= (0)::numeric)),
    CONSTRAINT detil_pembelian_jumlah_check CHECK ((jumlah > 0)),
    CONSTRAINT detil_pembelian_no_urut_check CHECK ((no_urut > 0)),
    CONSTRAINT detil_pembelian_subtotal_check CHECK ((subtotal >= (0)::numeric))
);


ALTER TABLE public.detil_pembelian OWNER TO postgres;

--
-- TOC entry 223 (class 1259 OID 17727)
-- Name: detil_penjualan; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.detil_penjualan (
    no_penjualan character varying(15) NOT NULL,
    no_urut integer NOT NULL,
    kode_produk character varying(10) NOT NULL,
    jumlah integer NOT NULL,
    harga numeric(12,2) NOT NULL,
    subtotal numeric(12,2) NOT NULL,
    CONSTRAINT detil_penjualan_harga_check CHECK ((harga >= (0)::numeric)),
    CONSTRAINT detil_penjualan_jumlah_check CHECK ((jumlah > 0)),
    CONSTRAINT detil_penjualan_no_urut_check CHECK ((no_urut > 0)),
    CONSTRAINT detil_penjualan_subtotal_check CHECK ((subtotal >= (0)::numeric))
);


ALTER TABLE public.detil_penjualan OWNER TO postgres;

--
-- TOC entry 220 (class 1259 OID 17691)
-- Name: pelanggan; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.pelanggan (
    kode_pelanggan character varying(10) NOT NULL,
    nama_pelanggan character varying(100) NOT NULL,
    no_telepon character varying(25),
    alamat text
);


ALTER TABLE public.pelanggan OWNER TO postgres;

--
-- TOC entry 224 (class 1259 OID 17752)
-- Name: pembelian; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.pembelian (
    no_pembelian character varying(15) NOT NULL,
    tgl_pembelian date NOT NULL,
    kode_supplier character varying(10) NOT NULL,
    total numeric(12,2) DEFAULT 0 NOT NULL,
    keterangan text,
    CONSTRAINT pembelian_total_check CHECK ((total >= (0)::numeric))
);


ALTER TABLE public.pembelian OWNER TO postgres;

--
-- TOC entry 222 (class 1259 OID 17709)
-- Name: penjualan; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.penjualan (
    no_penjualan character varying(15) NOT NULL,
    tgl_penjualan date NOT NULL,
    kode_pelanggan character varying(10) NOT NULL,
    total numeric(12,2) DEFAULT 0 NOT NULL,
    keterangan text,
    CONSTRAINT penjualan_total_check CHECK ((total >= (0)::numeric))
);


ALTER TABLE public.penjualan OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 17674)
-- Name: produk; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.produk (
    kode_produk character varying(10) NOT NULL,
    nama_produk character varying(100) NOT NULL,
    satuan character varying(20) NOT NULL,
    harga_jual numeric(12,2) NOT NULL,
    harga_beli numeric(12,2) NOT NULL,
    stok integer DEFAULT 0 NOT NULL,
    deskripsi text,
    CONSTRAINT produk_harga_beli_check CHECK ((harga_beli >= (0)::numeric)),
    CONSTRAINT produk_harga_jual_check CHECK ((harga_jual >= (0)::numeric)),
    CONSTRAINT produk_stok_check CHECK ((stok >= 0))
);


ALTER TABLE public.produk OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 17700)
-- Name: supplier; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.supplier (
    kode_supplier character varying(10) NOT NULL,
    nama_supplier character varying(100) NOT NULL,
    no_telepon character varying(25),
    alamat text
);


ALTER TABLE public.supplier OWNER TO postgres;

--
-- TOC entry 5073 (class 0 OID 17770)
-- Dependencies: 225
-- Data for Name: detil_pembelian; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.detil_pembelian (no_pembelian, no_urut, kode_produk, jumlah, harga, subtotal) FROM stdin;
B20261001	1	P001	2	47000.00	94000.00
B20261001	2	P002	3	2000.00	6000.00
B20261002	1	P004	1	69000.00	69000.00
B20261002	2	P005	5	1400.00	7000.00
\.


--
-- TOC entry 5071 (class 0 OID 17727)
-- Dependencies: 223
-- Data for Name: detil_penjualan; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.detil_penjualan (no_penjualan, no_urut, kode_produk, jumlah, harga, subtotal) FROM stdin;
J20261001	1	P001	2	58000.00	116000.00
J20261001	2	P002	1	3000.00	3000.00
J20261002	1	P003	3	6000.00	18000.00
J20261003	1	P001	1	58000.00	58000.00
\.


--
-- TOC entry 5068 (class 0 OID 17691)
-- Dependencies: 220
-- Data for Name: pelanggan; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.pelanggan (kode_pelanggan, nama_pelanggan, no_telepon, alamat) FROM stdin;
C001	Toko Maju	081200000001	Surabaya
C002	Koperasi Kampus	081200000002	Sidoarjo
C003	CV Sejahtera	081200000003	Gresik
\.


--
-- TOC entry 5072 (class 0 OID 17752)
-- Dependencies: 224
-- Data for Name: pembelian; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.pembelian (no_pembelian, tgl_pembelian, kode_supplier, total, keterangan) FROM stdin;
B20261001	2026-10-01	S001	101000.00	Transaksi contoh
B20261002	2026-10-03	S002	76000.00	Transaksi contoh
\.


--
-- TOC entry 5070 (class 0 OID 17709)
-- Dependencies: 222
-- Data for Name: penjualan; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.penjualan (no_penjualan, tgl_penjualan, kode_pelanggan, total, keterangan) FROM stdin;
J20261001	2026-10-01	C001	119000.00	Transaksi contoh
J20261002	2026-10-02	C002	18000.00	Transaksi contoh
J20261003	2026-11-06	C003	58000.00	ok
\.


--
-- TOC entry 5067 (class 0 OID 17674)
-- Dependencies: 219
-- Data for Name: produk; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.produk (kode_produk, nama_produk, satuan, harga_jual, harga_beli, stok, deskripsi) FROM stdin;
P002	Pulpen Hitam	buah	3500.00	2000.00	150	Pulpen tinta hitam
P003	Buku Tulis	buah	6000.00	3800.00	100	Buku tulis 58 lembar
P004	Tinta Printer	botol	85000.00	69000.00	25	Tinta printer hitam
P005	Map Plastik	buah	2500.00	1200.00	200	Map dokumen
P001	Kertas A4	rim	58000.00	47000.00	39	Kertas A4 80 gsm
\.


--
-- TOC entry 5069 (class 0 OID 17700)
-- Dependencies: 221
-- Data for Name: supplier; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.supplier (kode_supplier, nama_supplier, no_telepon, alamat) FROM stdin;
S001	PT Alat Tulis Nusantara	0317000001	Surabaya
S002	CV Kertas Jaya	0317000002	Sidoarjo
\.


--
-- TOC entry 4912 (class 2606 OID 17784)
-- Name: detil_pembelian detil_pembelian_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detil_pembelian
    ADD CONSTRAINT detil_pembelian_pkey PRIMARY KEY (no_pembelian, no_urut);


--
-- TOC entry 4906 (class 2606 OID 17741)
-- Name: detil_penjualan detil_penjualan_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detil_penjualan
    ADD CONSTRAINT detil_penjualan_pkey PRIMARY KEY (no_penjualan, no_urut);


--
-- TOC entry 4898 (class 2606 OID 17699)
-- Name: pelanggan pelanggan_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pelanggan
    ADD CONSTRAINT pelanggan_pkey PRIMARY KEY (kode_pelanggan);


--
-- TOC entry 4910 (class 2606 OID 17764)
-- Name: pembelian pembelian_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pembelian
    ADD CONSTRAINT pembelian_pkey PRIMARY KEY (no_pembelian);


--
-- TOC entry 4904 (class 2606 OID 17721)
-- Name: penjualan penjualan_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.penjualan
    ADD CONSTRAINT penjualan_pkey PRIMARY KEY (no_penjualan);


--
-- TOC entry 4896 (class 2606 OID 17690)
-- Name: produk produk_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.produk
    ADD CONSTRAINT produk_pkey PRIMARY KEY (kode_produk);


--
-- TOC entry 4900 (class 2606 OID 17708)
-- Name: supplier supplier_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.supplier
    ADD CONSTRAINT supplier_pkey PRIMARY KEY (kode_supplier);


--
-- TOC entry 4913 (class 1259 OID 17799)
-- Name: idx_detil_pembelian_produk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_detil_pembelian_produk ON public.detil_pembelian USING btree (kode_produk);


--
-- TOC entry 4907 (class 1259 OID 17798)
-- Name: idx_detil_penjualan_produk; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_detil_penjualan_produk ON public.detil_penjualan USING btree (kode_produk);


--
-- TOC entry 4908 (class 1259 OID 17797)
-- Name: idx_pembelian_tanggal; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_pembelian_tanggal ON public.pembelian USING btree (tgl_pembelian);


--
-- TOC entry 4901 (class 1259 OID 17796)
-- Name: idx_penjualan_pelanggan; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_penjualan_pelanggan ON public.penjualan USING btree (kode_pelanggan);


--
-- TOC entry 4902 (class 1259 OID 17795)
-- Name: idx_penjualan_tanggal; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_penjualan_tanggal ON public.penjualan USING btree (tgl_penjualan);


--
-- TOC entry 4918 (class 2606 OID 17790)
-- Name: detil_pembelian detil_pembelian_kode_produk_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detil_pembelian
    ADD CONSTRAINT detil_pembelian_kode_produk_fkey FOREIGN KEY (kode_produk) REFERENCES public.produk(kode_produk);


--
-- TOC entry 4919 (class 2606 OID 17785)
-- Name: detil_pembelian detil_pembelian_no_pembelian_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detil_pembelian
    ADD CONSTRAINT detil_pembelian_no_pembelian_fkey FOREIGN KEY (no_pembelian) REFERENCES public.pembelian(no_pembelian) ON DELETE CASCADE;


--
-- TOC entry 4915 (class 2606 OID 17747)
-- Name: detil_penjualan detil_penjualan_kode_produk_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detil_penjualan
    ADD CONSTRAINT detil_penjualan_kode_produk_fkey FOREIGN KEY (kode_produk) REFERENCES public.produk(kode_produk);


--
-- TOC entry 4916 (class 2606 OID 17742)
-- Name: detil_penjualan detil_penjualan_no_penjualan_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detil_penjualan
    ADD CONSTRAINT detil_penjualan_no_penjualan_fkey FOREIGN KEY (no_penjualan) REFERENCES public.penjualan(no_penjualan) ON DELETE CASCADE;


--
-- TOC entry 4917 (class 2606 OID 17765)
-- Name: pembelian pembelian_kode_supplier_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pembelian
    ADD CONSTRAINT pembelian_kode_supplier_fkey FOREIGN KEY (kode_supplier) REFERENCES public.supplier(kode_supplier);


--
-- TOC entry 4914 (class 2606 OID 17722)
-- Name: penjualan penjualan_kode_pelanggan_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.penjualan
    ADD CONSTRAINT penjualan_kode_pelanggan_fkey FOREIGN KEY (kode_pelanggan) REFERENCES public.pelanggan(kode_pelanggan);


-- Completed on 2026-11-15 16:32:06

--
-- PostgreSQL database dump complete
--

\unrestrict pGsx7jz8RKgxp3TIMf8cosJrC4fJtt0WbcliEzDctTwNFfmGaD70CEaCwNWhxx5

