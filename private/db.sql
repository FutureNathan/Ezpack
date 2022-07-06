-- ################################################################################################# --- USERS

CREATE TABLE users (
  
  user_id                     INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY (START WITH 2022),
  
  user_name                   TEXT NOT NULL,
  
  user_email                  TEXT UNIQUE,
  user_password               TEXT NOT NULL,
  
  user_email_confirmed        BOOLEAN NOT NULL DEFAULT FALSE,
  user_active                 BOOLEAN NOT NULL DEFAULT TRUE
);


-- ################################################################################################# --- PRODUCTS

CREATE TABLE products (
  
  prod_id                 INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY (START WITH 2022),
  
  prod_owner_id           INT NOT NULL REFERENCES users (user_id)
                          ON UPDATE CASCADE
                          ON DELETE CASCADE,
  
  prod_name               TEXT NOT NULL,
  
  prod_type               TEXT NOT NULL,
  prod_length             INT NOT NULL,
  prod_width              INT NOT NULL,
  prod_height             INT NOT NULL,
  
  prod_box_only_price     INT NOT NULL,
  prod_basic_price        INT NOT NULL,
  prod_fragile_price      INT NOT NULL,
  prod_custom_price       INT NOT NULL
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
  subscription_billing_period       TEXT NOT NULL,
  
  subscription_active               BOOLEAN NOT NULL DEFAULT FALSE,
  
  subscription_stripe_sub_id        TEXT NOT NULL,
  subscription_stripe_product_id    TEXT NOT NULL,
  subscription_stripe_price_id      TEXT NOT NULL,
  subscription_stripe_customer_id   TEXT NOT NULL,
  subscription_stripe_coupon_id     TEXT
  
  UNIQUE (subscription_user_id, subscription_stripe_product_id)
);

-- ################################################################################################# --- INVOICES

CREATE TABLE invoices (

  invoice_id                    INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY (START WITH 120768),
  
  invoice_total                 DECIMAL NOT NULL,
  
  invoice_subscription_title    TEXT NOT NULL,
  invoice_billing_period        TEXT NOT NULL,
  
  invoice_issue_date            TIMESTAMPTZ NOT NULL,
  invoice_sub_renewal_date      TIMESTAMPTZ NOT NULL,
  
  invoice_user_full_name        TEXT NOT NULL,
  invoice_user_email            TEXT NOT NULL,
  
  invoice_stripe_invoice_id     TEXT NOT NULL,
  invoice_stripe_sub_id         TEXT NOT NULL,
  invoice_stripe_product_id     TEXT NOT NULL,
  invoice_stripe_price_id       TEXT NOT NULL,
  invoice_stripe_customer_id    TEXT NOT NULL,
  
  invoice_stripe_coupon_id      TEXT,
  invoice_stripe_coupon_name    TEXT,
  
  invoice_acc_id                INT NOT NULL
                                REFERENCES users (user_id)
                                ON UPDATE CASCADE
);






