create table raspored
(
    id_raspored     serial not null
        constraint raspored_pk
            primary key,
    pocetak_r       timestamp,
    kraj_r          timestamp,
    naziv_rasporeda varchar
);

alter table raspored
    owner to postgres;

INSERT INTO public.raspored (id_raspored, pocetak_r, kraj_r, naziv_rasporeda) VALUES (1, '2022-02-17 08:00:00.000000', null, 'Ljetni semestar 2022.');