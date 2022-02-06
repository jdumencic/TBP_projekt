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

INSERT INTO public.kolegij (id_kolegij, pocetak_k, kraj_k, naziv_kolegija, fk_semestar) VALUES (4, '2022-02-06 12:45:21.456680', null, 'VAS', 3);
INSERT INTO public.kolegij (id_kolegij, pocetak_k, kraj_k, naziv_kolegija, fk_semestar) VALUES (5, '2022-02-06 12:45:27.475158', null, 'MIS', 3);
INSERT INTO public.kolegij (id_kolegij, pocetak_k, kraj_k, naziv_kolegija, fk_semestar) VALUES (6, '2022-02-06 12:46:54.993302', '2022-02-06 12:55:12.724938', 'RG', 3);