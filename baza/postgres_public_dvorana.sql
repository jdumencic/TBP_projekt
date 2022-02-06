create table dvorana
(
    id_dvorana           serial not null
        constraint dvorana_pk
            primary key,
    naziv                varchar,
    kat                  integer,
    br_studenata_dvorana integer
);

alter table dvorana
    owner to postgres;

INSERT INTO public.dvorana (id_dvorana, naziv, kat, br_studenata_dvorana) VALUES (1, 'D1', 2, 200);
INSERT INTO public.dvorana (id_dvorana, naziv, kat, br_studenata_dvorana) VALUES (2, 'D2', 1, 50);
INSERT INTO public.dvorana (id_dvorana, naziv, kat, br_studenata_dvorana) VALUES (3, 'D8', 0, 14);
INSERT INTO public.dvorana (id_dvorana, naziv, kat, br_studenata_dvorana) VALUES (4, 'D4', 1, 30);
INSERT INTO public.dvorana (id_dvorana, naziv, kat, br_studenata_dvorana) VALUES (5, 'D14', 0, 15);
INSERT INTO public.dvorana (id_dvorana, naziv, kat, br_studenata_dvorana) VALUES (6, 'D6', 1, 75);