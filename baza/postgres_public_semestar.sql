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

INSERT INTO public.semestar (id_semestar, pocetak_s, kraj_s, naziv_semestra) VALUES (3, '2022-02-06 12:44:29.067228', null, 'Zimski semestar');