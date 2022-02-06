create table biljeske
(
    id_biljeske serial not null
        constraint biljeske_pk
            primary key,
    pocetak_b   timestamp,
    kraj_b      timestamp,
    tekst       text,
    fk_zapis    integer
        constraint biljeske_zapis_id_zapis_fk
            references zapis
);

alter table biljeske
    owner to postgres;

INSERT INTO public.biljeske (id_biljeske, pocetak_b, kraj_b, tekst, fk_zapis) VALUES (7, '2022-02-06 12:49:04.662572', null, 'Dobrodošli na predavanje iz kolegija MIS!', 8);
INSERT INTO public.biljeske (id_biljeske, pocetak_b, kraj_b, tekst, fk_zapis) VALUES (8, '2022-02-06 12:50:08.246651', null, 'Dobrodošli na seminar iz kolegija MIS!', 10);
INSERT INTO public.biljeske (id_biljeske, pocetak_b, kraj_b, tekst, fk_zapis) VALUES (9, '2022-02-06 12:50:50.370015', null, 'Dobrodošli na predavanje iz kolegija VAS!', 11);
INSERT INTO public.biljeske (id_biljeske, pocetak_b, kraj_b, tekst, fk_zapis) VALUES (10, '2022-02-06 12:51:29.402009', null, 'Dobrodošli na labos iz kolegija VAS!', 12);
INSERT INTO public.biljeske (id_biljeske, pocetak_b, kraj_b, tekst, fk_zapis) VALUES (11, '2022-02-06 12:53:24.133986', null, 'Predavanje se održava online!', 8);