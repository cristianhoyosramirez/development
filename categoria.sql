--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.25
-- Dumped by pg_dump version 9.3.25
-- Started on 2025-01-27 13:43:00

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- TOC entry 5378 (class 0 OID 908881)
-- Dependencies: 259
-- Data for Name: categoria; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.categoria (codigocategoria, nombrecategoria, descripcioncategoria, estadocategoria, permitir_categoria, impresora, id, subcategoria, orden) VALUES ('3', 'LASAGNAS', '', true, true, 1, 3, false, NULL);
INSERT INTO public.categoria (codigocategoria, nombrecategoria, descripcioncategoria, estadocategoria, permitir_categoria, impresora, id, subcategoria, orden) VALUES ('4', 'ASADOS', '', true, true, 1, 4, false, NULL);
INSERT INTO public.categoria (codigocategoria, nombrecategoria, descripcioncategoria, estadocategoria, permitir_categoria, impresora, id, subcategoria, orden) VALUES ('5', 'CHUZOS', '', true, true, 1, 5, false, NULL);
INSERT INTO public.categoria (codigocategoria, nombrecategoria, descripcioncategoria, estadocategoria, permitir_categoria, impresora, id, subcategoria, orden) VALUES ('6', 'ALMUERZOS', '', true, true, 1, 6, false, NULL);
INSERT INTO public.categoria (codigocategoria, nombrecategoria, descripcioncategoria, estadocategoria, permitir_categoria, impresora, id, subcategoria, orden) VALUES ('10', 'ADICIONES', '', true, true, 1, 10, false, NULL);
INSERT INTO public.categoria (codigocategoria, nombrecategoria, descripcioncategoria, estadocategoria, permitir_categoria, impresora, id, subcategoria, orden) VALUES ('11', 'ALITAS ', '', true, true, 1, 11, false, NULL);
INSERT INTO public.categoria (codigocategoria, nombrecategoria, descripcioncategoria, estadocategoria, permitir_categoria, impresora, id, subcategoria, orden) VALUES ('12', 'ESPECIALES', '', true, true, 1, 12, false, NULL);
INSERT INTO public.categoria (codigocategoria, nombrecategoria, descripcioncategoria, estadocategoria, permitir_categoria, impresora, id, subcategoria, orden) VALUES ('13', 'PERROS', '', true, true, 1, 13, false, NULL);
INSERT INTO public.categoria (codigocategoria, nombrecategoria, descripcioncategoria, estadocategoria, permitir_categoria, impresora, id, subcategoria, orden) VALUES ('14', 'MALTEADAS', '', true, true, 1, 14, false, NULL);
INSERT INTO public.categoria (codigocategoria, nombrecategoria, descripcioncategoria, estadocategoria, permitir_categoria, impresora, id, subcategoria, orden) VALUES ('15', 'GASEOSA', '', true, true, 1, 15, false, NULL);
INSERT INTO public.categoria (codigocategoria, nombrecategoria, descripcioncategoria, estadocategoria, permitir_categoria, impresora, id, subcategoria, orden) VALUES ('16', 'CERVEZAS', '', true, true, 1, 16, false, NULL);
INSERT INTO public.categoria (codigocategoria, nombrecategoria, descripcioncategoria, estadocategoria, permitir_categoria, impresora, id, subcategoria, orden) VALUES ('17', 'SODAS SABORIZADAS', '', true, true, 1, 17, false, NULL);
INSERT INTO public.categoria (codigocategoria, nombrecategoria, descripcioncategoria, estadocategoria, permitir_categoria, impresora, id, subcategoria, orden) VALUES ('18', 'JUGOS', '', true, true, 1, 18, false, NULL);
INSERT INTO public.categoria (codigocategoria, nombrecategoria, descripcioncategoria, estadocategoria, permitir_categoria, impresora, id, subcategoria, orden) VALUES ('2', 'HAMBURGUESAS', '', true, true, 1, 2, false, NULL);
INSERT INTO public.categoria (codigocategoria, nombrecategoria, descripcioncategoria, estadocategoria, permitir_categoria, impresora, id, subcategoria, orden) VALUES ('1', 'GENERAL', NULL, true, true, 1, 1, false, NULL);
INSERT INTO public.categoria (codigocategoria, nombrecategoria, descripcioncategoria, estadocategoria, permitir_categoria, impresora, id, subcategoria, orden) VALUES ('30', 'ENTRADAS', '', true, true, 1, 19, false, NULL);
INSERT INTO public.categoria (codigocategoria, nombrecategoria, descripcioncategoria, estadocategoria, permitir_categoria, impresora, id, subcategoria, orden) VALUES ('7', 'PORCIONES', '', true, true, 1, 7, false, NULL);
INSERT INTO public.categoria (codigocategoria, nombrecategoria, descripcioncategoria, estadocategoria, permitir_categoria, impresora, id, subcategoria, orden) VALUES ('9', 'ADICIONALES', '', true, false, 1, 9, false, NULL);
INSERT INTO public.categoria (codigocategoria, nombrecategoria, descripcioncategoria, estadocategoria, permitir_categoria, impresora, id, subcategoria, orden) VALUES ('8', 'PROTEINA ', '', true, false, 1, 8, false, NULL);
INSERT INTO public.categoria (codigocategoria, nombrecategoria, descripcioncategoria, estadocategoria, permitir_categoria, impresora, id, subcategoria, orden) VALUES ('31', 'BEBIDAS CALIENTES', '', true, true, 1, 20, false, NULL);


--
-- TOC entry 5386 (class 0 OID 0)
-- Dependencies: 509
-- Name: categoria_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.categoria_id_seq', 20, true);


-- Completed on 2025-01-27 13:43:00

--
-- PostgreSQL database dump complete
--

