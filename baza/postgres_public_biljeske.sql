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

INSERT INTO public.biljeske (id_biljeske, pocetak_b, kraj_b, tekst, fk_zapis) VALUES (2, '2022-02-17 08:00:00.000000', null, null, 4);
INSERT INTO public.biljeske (id_biljeske, pocetak_b, kraj_b, tekst, fk_zapis) VALUES (3, '2022-02-17 08:00:00.000000', null, 'Dobrodošli na kolegij VAS!', 5);