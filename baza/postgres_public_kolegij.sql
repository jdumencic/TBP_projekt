create table kolegij
(
    id_kolegij     serial not null
        constraint kolegij_pk
            primary key,
    pocetak_k      timestamp,
    kraj_k         timestamp,
    naziv_kolegija varchar,
    fk_semestar    integer
        constraint kolegij_semestar_id_semestar_fk
            references semestar
);

alter table kolegij
    owner to postgres;

INSERT INTO public.kolegij (id_kolegij, pocetak_k, kraj_k, naziv_kolegija, fk_semestar) VALUES (1, '2022-02-17 08:00:00.000000', null, 'VAS', 1);
INSERT INTO public.kolegij (id_kolegij, pocetak_k, kraj_k, naziv_kolegija, fk_semestar) VALUES (2, '2022-02-17 08:00:00.000000', null, 'TBP', 1);