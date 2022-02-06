create table semestar
(
    id_semestar    serial not null
        constraint semestar_pk
            primary key,
    pocetak_s      timestamp,
    kraj_s         timestamp,
    naziv_semestra varchar
);

alter table semestar
    owner to postgres;

INSERT INTO public.semestar (id_semestar, pocetak_s, kraj_s, naziv_semestra) VALUES (1, '2022-02-21 08:00:00.000000', '2022-06-17 16:00:00.000000', '2.');