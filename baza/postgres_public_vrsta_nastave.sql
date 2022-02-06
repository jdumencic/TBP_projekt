create table vrsta_nastave
(
    id_vrsta    serial not null
        constraint vrsta_nastave_pk
            primary key,
    naziv_vrsta varchar
);

alter table vrsta_nastave
    owner to postgres;

INSERT INTO public.vrsta_nastave (id_vrsta, naziv_vrsta) VALUES (1, 'predavanje');
INSERT INTO public.vrsta_nastave (id_vrsta, naziv_vrsta) VALUES (2, 'seminar');
INSERT INTO public.vrsta_nastave (id_vrsta, naziv_vrsta) VALUES (3, 'labos');