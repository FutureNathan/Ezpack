-- ################################################################################################# --- USERS

CREATE TABLE users (
  
  user_id                         INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY (START WITH 2022),
  
  user_name                       TEXT NOT NULL,
  
  user_password                   TEXT NOT NULL,
  user_email                      TEXT NOT NULL UNIQUE,
  user_phone_number               TEXT NOT NULL UNIQUE,
  
  user_email_confirmed            BOOLEAN NOT NULL DEFAULT TRUE,
  user_active                     BOOLEAN NOT NULL DEFAULT TRUE,
  
  user_lifetime_subscription      BOOLEAN NOT NULL DEFAULT FALSE,
  
  user_remember_me_token          TEXT
);


-- ################################################################################################# --- VENDOR PRODUCTS

CREATE TABLE vendor_products (
  
  vendor_prod_id                 INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY (START WITH 2022),
  
  vendor_prod_owner_id           INT,
  
  vendor_prod_name               TEXT NOT NULL,
  
  vendor_prod_type               TEXT NOT NULL  CHECK (vendor_prod_type IN ('ups')),
  
  vendor_prod_length             DECIMAL NOT NULL,
  vendor_prod_width              DECIMAL NOT NULL,
  vendor_prod_height             DECIMAL NOT NULL,
  
  vendor_prod_max_weight         DECIMAL,
  
  vendor_prod_price_box_only     INT NOT NULL,
  vendor_prod_price_standard     INT NOT NULL,
  vendor_prod_price_basic        INT NOT NULL,
  vendor_prod_price_fragile      INT NOT NULL,
  vendor_prod_price_custom       INT NOT NULL
);

-- ################################################################################################# --- EDITED VENDOR PRODUCTS

CREATE TABLE edited_vendor_products (
  
  edited_vendor_prod_id                 INT REFERENCES vendor_products (vendor_prod_id)
                                        ON UPDATE CASCADE
                                        ON DELETE CASCADE,
  
  edited_vendor_prod_owner_id           INT REFERENCES users (user_id)
                                        ON UPDATE CASCADE
                                        ON DELETE CASCADE,
  
  edited_vendor_prod_price_box_only     INT NOT NULL,
  edited_vendor_prod_price_standard     INT NOT NULL,
  edited_vendor_prod_price_basic        INT NOT NULL,
  edited_vendor_prod_price_fragile      INT NOT NULL,
  edited_vendor_prod_price_custom       INT NOT NULL,
  
  edited_vendor_prod_availability       BOOLEAN NOT NULL DEFAULT TRUE
);

-- ################################################################################################# --- PRODUCTS

CREATE TABLE custom_products (
  
  custom_prod_id                 INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY (START WITH 2022),
  
  custom_prod_owner_id           INT REFERENCES users (user_id)
                                  ON UPDATE CASCADE
                                  ON DELETE CASCADE,
  
  custom_prod_name               TEXT NOT NULL,
  
  custom_prod_type               TEXT NOT NULL  CHECK (custom_prod_type IN ('custom')),
  
  custom_prod_length             DECIMAL NOT NULL,
  custom_prod_width              DECIMAL NOT NULL,
  custom_prod_height             DECIMAL NOT NULL,
  
  custom_prod_max_weight         DECIMAL,
  
  custom_prod_price_box_only     INT NOT NULL,
  custom_prod_price_standard     INT NOT NULL,
  custom_prod_price_basic        INT NOT NULL,
  custom_prod_price_fragile      INT NOT NULL,
  custom_prod_price_custom       INT NOT NULL,
  
  custom_prod_availability       BOOLEAN NOT NULL DEFAULT TRUE
);

-- ################################################################################################# --- SUBSCRIPTIONS

CREATE TABLE subscriptions (
  
  subscription_id                   INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
  
  subscription_user_id              INT NOT NULL
                                    REFERENCES users (user_id)
                                    ON UPDATE CASCADE
                                    ON DELETE CASCADE,
  
  subscription_title                TEXT NOT NULL,
  
  subscription_start_date           TIMESTAMPTZ NOT NULL,
  subscription_renewal_date         TIMESTAMPTZ NOT NULL,
  subscription_billing_period       TEXT NOT NULL,        -- monthly, annually, etc.
  
  subscription_active               BOOLEAN NOT NULL DEFAULT TRUE,
  
  subscription_stripe_sub_id        TEXT NOT NULL,
  subscription_stripe_product_id    TEXT NOT NULL,
  subscription_stripe_price_id      TEXT NOT NULL,
  subscription_stripe_customer_id   TEXT NOT NULL,
  
  UNIQUE (subscription_user_id, subscription_stripe_product_id)
);

-- ################################################################################################# --- INVOICES

CREATE TABLE invoices (

  invoice_id                    INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY (START WITH 120768),
  
  invoice_user_id               INT NOT NULL
                                REFERENCES users (user_id)
                                ON UPDATE CASCADE,
  
  invoice_total_amount          INT NOT NULL,
  
  invoice_issue_date            TIMESTAMPTZ NOT NULL,
  
  invoice_sub_renewal_date      TIMESTAMPTZ NOT NULL,
  invoice_billing_period        TEXT NOT NULL,
  
  invoice_customer_details      JSONB NOT NULL, /*
                                                  {
                                                    "user_name"           => "Full name",
                                                    "user_email"          => "user@gmail.com",
                                                    "user_phone_number"   => "+355 6974070444",
                                                    
                                                    "billing_address" => {
                                                      "address_street_and_number"   =>  "Blv. Gjergj Fishta Nd.23",
                                                      "address_zip_code"            =>  "1013",
                                                      "address_city"                =>  "Tiranë",
                                                      "address_country"             =>  "Shqipëri"
                                                    }
                                                  }
                                                */
  
  invoice_stripe_details        JSONB NOT NULL  /*
                                                  {
                                                    "stripe_invoice_id"         => "",
                                                    "stripe_subscription_id"    => "",
                                                    "stripe_product_id"         => "",
                                                    "stripe_price_id"           => "",
                                                    "stripe_customer_id"        => ""
                                                  }
                                                */
);

-- ################################################################################################# HISTORY

CREATE TABLE history (

  history_id                 INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY (START WITH 1),
  
  history_length             DECIMAL NOT NULL,
  history_width              DECIMAL NOT NULL,
  history_height             DECIMAL NOT NULL,
  
  history_packing_level      TEXT NOT NULL,
  
  history_user_id            INT NOT NULL
                             REFERENCES users (user_id)
                             ON UPDATE CASCADE
)




