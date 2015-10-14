/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     14/10/2015 02:33:29 p. m.                    */
/*==============================================================*/


drop index index_1 on actividades;

drop table if exists actividades;

drop index index_1 on calendario_actividades;

drop table if exists calendario_actividades;

drop index index_1 on calendario_actividades_contenido;

drop table if exists calendario_actividades_contenido;

drop index index_1 on estados;

drop table if exists estados;

drop index index_1 on municipios;

drop table if exists municipios;

drop index index_1 on tipos_actividades;

drop table if exists tipos_actividades;

drop index index_2 on tipos_contenido;

drop table if exists tipos_contenido;

drop index index_1 on unidades_negocio;

drop table if exists unidades_negocio;

/*==============================================================*/
/* Table: actividades                                           */
/*==============================================================*/
create table actividades
(
   cve_actividad        int not null,
   cve_tipo             int not null,
   nombre               varchar(80) not null,
   descripcion          varchar(200) not null,
   activo               bit not null,
   primary key (cve_actividad)
);

/*==============================================================*/
/* Index: index_1                                               */
/*==============================================================*/
create index index_1 on actividades
(
   cve_actividad
);

/*==============================================================*/
/* Table: calendario_actividades                                */
/*==============================================================*/
create table calendario_actividades
(
   cve_calendario       int not null,
   cve_actividad        int not null,
   fecha_inicio         datetime not null,
   fecha_fin            datetime not null,
   lugar                varchar(40),
   cve_estado           int not null,
   cve_municipio        int not null,
   imagen_portada       varchar(80),
   precio               double,
   cupo_maximo          int,
   observaciones        varchar(250),
   fecha_alta           datetime not null,
   activo               bit not null,
   primary key (cve_calendario)
);

/*==============================================================*/
/* Index: index_1                                               */
/*==============================================================*/
create index index_1 on calendario_actividades
(
   cve_calendario
);

/*==============================================================*/
/* Table: calendario_actividades_contenido                      */
/*==============================================================*/
create table calendario_actividades_contenido
(
   cve_calendario       int not null,
   cve_tipo_contenido   int not null,
   url                  varchar(80) not null,
   activo               bit not null,
   primary key (cve_calendario, cve_tipo_contenido)
);

/*==============================================================*/
/* Index: index_1                                               */
/*==============================================================*/
create index index_1 on calendario_actividades_contenido
(
   cve_calendario
);

/*==============================================================*/
/* Table: estados                                               */
/*==============================================================*/
create table estados
(
   cve_estado           int not null,
   nombre               varchar(30) not null,
   activo               bit not null,
   primary key (cve_estado)
);

/*==============================================================*/
/* Index: index_1                                               */
/*==============================================================*/
create index index_1 on estados
(
   cve_estado
);

/*==============================================================*/
/* Table: municipios                                            */
/*==============================================================*/
create table municipios
(
   cve_estado           int not null,
   cve_municipio        int not null,
   nombre               varchar(45) not null,
   activo               bit not null,
   primary key (cve_estado, cve_municipio)
);

/*==============================================================*/
/* Index: index_1                                               */
/*==============================================================*/
create index index_1 on municipios
(
   cve_estado,
   cve_municipio
);

/*==============================================================*/
/* Table: tipos_actividades                                     */
/*==============================================================*/
create table tipos_actividades
(
   cve_tipo             int not null,
   cve_unidad_negocio   int not null,
   nombre               varchar(50) not null,
   activo               bit not null,
   primary key (cve_tipo)
);

/*==============================================================*/
/* Index: index_1                                               */
/*==============================================================*/
create index index_1 on tipos_actividades
(
   cve_tipo
);

/*==============================================================*/
/* Table: tipos_contenido                                       */
/*==============================================================*/
create table tipos_contenido
(
   cve_tipo_contenido   int not null,
   nombre               varchar(35) not null,
   activo               bit not null,
   primary key (cve_tipo_contenido)
);

/*==============================================================*/
/* Index: index_2                                               */
/*==============================================================*/
create index index_2 on tipos_contenido
(
   cve_tipo_contenido
);

/*==============================================================*/
/* Table: unidades_negocio                                      */
/*==============================================================*/
create table unidades_negocio
(
   cve_unidad_negocio   int not null,
   nombre               varchar(50) not null,
   activo               bit not null,
   primary key (cve_unidad_negocio)
);

alter table unidades_negocio comment 'Tabla para guardar los 2 giros del negocio, el de capacitaci';

/*==============================================================*/
/* Index: index_1                                               */
/*==============================================================*/
create index index_1 on unidades_negocio
(
   cve_unidad_negocio
);

alter table actividades add constraint fk_reference_2 foreign key (cve_tipo)
      references tipos_actividades (cve_tipo) on delete restrict on update restrict;

alter table calendario_actividades add constraint fk_reference_3 foreign key (cve_actividad)
      references actividades (cve_actividad) on delete restrict on update restrict;

alter table calendario_actividades add constraint fk_reference_5 foreign key (cve_estado, cve_municipio)
      references municipios (cve_estado, cve_municipio) on delete restrict on update restrict;

alter table calendario_actividades_contenido add constraint fk_reference_6 foreign key (cve_calendario)
      references calendario_actividades (cve_calendario) on delete restrict on update restrict;

alter table calendario_actividades_contenido add constraint fk_reference_7 foreign key (cve_tipo_contenido)
      references tipos_contenido (cve_tipo_contenido) on delete restrict on update restrict;

alter table municipios add constraint fk_reference_4 foreign key (cve_estado)
      references estados (cve_estado) on delete restrict on update restrict;

alter table tipos_actividades add constraint fk_reference_1 foreign key (cve_unidad_negocio)
      references unidades_negocio (cve_unidad_negocio) on delete restrict on update restrict;

