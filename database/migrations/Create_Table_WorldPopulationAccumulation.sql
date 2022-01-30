CREATE TABLE WorldPopulationAccumulation (
    Level0     VARCHAR(512)  NOT NULL,
    Level1     VARCHAR(512)      NUll,
    Level2     VARCHAR(512)      NULL,
    Displayed  VARCHAR(2048) NOT NULL,
    Population BIGINT        NOT NULL,
    DataDate   DATE              NULL,
    Notes      TEXT              NULL,
    PRIMARY KEY (Level0, Level1, Level2)
);
CREATE INDEX LV0 ON WorldPopulationAccumulation;
CREATE INDEX LV1 ON WorldPopulationAccumulation;
CREATE INDEX LV2 ON WorldPopulationAccumulation;
