CREATE TABLE "posts" (
                                "id" serial NOT NULL,
                                "post_id" integer NOT NULL,
                                "dhash" VARCHAR(255) NOT NULL,
                                "channel_id" VARCHAR(255) NOT NULL,
                                CONSTRAINT "posts_pk" PRIMARY KEY ("id")
) WITH (
      OIDS=FALSE
    );



CREATE TABLE "channel_to_admin" (
                                           "admin_id" integer NOT NULL,
                                           "channel_id" integer NOT NULL
) WITH (
      OIDS=FALSE
    );



CREATE TABLE "channels" (
                                   "id" serial NOT NULL,
                                   "name" VARCHAR(255) NOT NULL,
                                   "channel_id" integer NOT NULL UNIQUE,
                                   CONSTRAINT "channels_pk" PRIMARY KEY ("id")
) WITH (
      OIDS=FALSE
    );



CREATE TABLE "users" (
                                "id" serial NOT NULL,
                                "user_id" integer NOT NULL UNIQUE,
                                "first_name" VARCHAR(255) NOT NULL,
                                "last_name" VARCHAR(255),
                                "username" VARCHAR(255) NOT NULL,
                                "chat_id" integer NOT NULL,
                                "admin_id" integer UNIQUE,
                                CONSTRAINT "users_pk" PRIMARY KEY ("id")
) WITH (
      OIDS=FALSE
    );



ALTER TABLE "posts" ADD CONSTRAINT "posts_fk0" FOREIGN KEY ("channel_id") REFERENCES "channels"("channel_id");

ALTER TABLE "channel_to_admin" ADD CONSTRAINT "channel_to_admin_fk0" FOREIGN KEY ("admin_id") REFERENCES "users"("admin_id");
ALTER TABLE "channel_to_admin" ADD CONSTRAINT "channel_to_admin_fk1" FOREIGN KEY ("channel_id") REFERENCES "channels"("channel_id");
CREATE EXTENSION pg_trgm;
CREATE INDEX trgm_idx ON "posts" USING gist (dhash gist_trgm_ops);

CREATE OR REPLACE FUNCTION delete_old_posts()
    RETURNS TRIGGER AS
$$
BEGIN
    IF (SELECT count(*) FROM posts) > 1500 THEN
        DELETE FROM posts
        WHERE id = (SELECT id from posts ORDER BY id LIMIT 1);
    END IF;
    RETURN NEW;
END;
$$
    LANGUAGE plpgsql;

CREATE TRIGGER check_post_count
    AFTER INSERT ON posts
    FOR EACH ROW
EXECUTE FUNCTION delete_old_posts();





SELECT * FROM posts ORDER BY SIMILARITY(dhash, '11e5d1d0d08850d0') DESC LIMIT 5;







