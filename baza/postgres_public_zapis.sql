create table zapis
(
    id_zapis           serial not null
        constraint zapis_pk
            primary key,
    pocetak_z          timestamp,
    kraj_z             timestamp,
    zapocinje          time,
    zavrsava           time,
    dan                varchar,
    br_studenata_zapis integer,
    fk_vrsta           integer
        constraint zapis_vrsta_nastave_id_vrsta_fk
            references vrsta_nastave,
    fk_dvorana         integer
        constraint zapis_dvorana_id_dvorana_fk
            references dvorana,
    fk_raspored        integer
        constraint zapis_raspored_id_raspored_fk
            references raspored,
    fk_kolegij         integer
        constraint zapis_kolegij_id_kolegij_fk
            references kolegij
);

alter table zapis
    owner to postgres;

INSERT INTO public.zapis (id_zapis, pocetak_z, kraj_z, zapocinje, zavrsava, dan, br_studenata_zapis, fk_vrsta, fk_dvorana, fk_raspored, fk_kolegij) VALUES (8, '2022-02-06 12:49:04.662572', null, '08:00:00', '10:00:00', 'Ponedjeljak', 88, 1, 1, 1, 5);
INSERT INTO public.zapis (id_zapis, pocetak_z, kraj_z, zapocinje, zavrsava, dan, br_studenata_zapis, fk_vrsta, fk_dvorana, fk_raspored, fk_kolegij) VALUES (10, '2022-02-06 12:50:08.246651', null, '11:00:00', '12:00:00', 'Utorak', 29, 2, 4, 1, 5);
INSERT INTO public.zapis (id_zapis, pocetak_z, kraj_z, zapocinje, zavrsava, dan, br_studenata_zapis, fk_vrsta, fk_dvorana, fk_raspored, fk_kolegij) VALUES (11, '2022-02-06 12:50:50.370015', null, '14:00:00', '16:00:00', 'Četvrtak', 50, 1, 6, 1, 4);
INSERT INTO public.zapis (id_zapis, pocetak_z, kraj_z, zapocinje, zavrsava, dan, br_studenata_zapis, fk_vrsta, fk_dvorana, fk_raspored, fk_kolegij) VALUES (12, '2022-02-06 12:51:29.402009', '2022-02-06 12:54:14.325257', '16:00:00', '17:30:00', 'Srijeda', 15, 3, 5, 1, 4);