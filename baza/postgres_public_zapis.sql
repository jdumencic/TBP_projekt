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

INSERT INTO public.zapis (id_zapis, pocetak_z, kraj_z, zapocinje, zavrsava, dan, br_studenata_zapis, fk_vrsta, fk_dvorana, fk_raspored, fk_kolegij) VALUES (4, '2022-02-17 08:00:00.000000', null, '09:00:00', '11:00:00', 'Petak', 28, 2, 2, 1, 1);
INSERT INTO public.zapis (id_zapis, pocetak_z, kraj_z, zapocinje, zavrsava, dan, br_studenata_zapis, fk_vrsta, fk_dvorana, fk_raspored, fk_kolegij) VALUES (2, '2022-02-17 08:00:00.000000', null, '15:00:00', '16:30:00', 'Utorak', 10, 3, 3, 1, 2);
INSERT INTO public.zapis (id_zapis, pocetak_z, kraj_z, zapocinje, zavrsava, dan, br_studenata_zapis, fk_vrsta, fk_dvorana, fk_raspored, fk_kolegij) VALUES (5, '2022-02-17 08:00:00.000000', null, '11:00:00', '12:00:00', 'Ponedjeljak', 50, 2, 2, 1, 1);
INSERT INTO public.zapis (id_zapis, pocetak_z, kraj_z, zapocinje, zavrsava, dan, br_studenata_zapis, fk_vrsta, fk_dvorana, fk_raspored, fk_kolegij) VALUES (1, '2022-02-17 08:00:00.000000', null, '09:10:00', '10:00:00', 'Srijeda', 50, 1, 1, 1, 1);