CREATE TABLE "changelog" (
  "id" serial PRIMARY KEY,
  "file" character varying NOT NULL,
  "description" character varying NULL,
  "query" text NOT NULL,
  "error" character varying NULL,
  "executed" boolean NOT NULL DEFAULT '0',
  "ins_timestamp" timestamp NULL,
  "ins_dt" timestamp NOT NULL,
  "upd_dt" timestamp NOT NULL,
  "del_flag" boolean NOT NULL DEFAULT '0'
);
