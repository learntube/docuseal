CREATE TABLE fe_users (
	docuseal_signatures int(11) unsigned NOT NULL DEFAULT '0',
	docuseal_id varchar(255) NOT NULL DEFAULT ''
);

CREATE TABLE tx_docuseal_domain_model_signatures (
	fe_user int(11) unsigned DEFAULT '0' NOT NULL,
	template_id varchar(255) NOT NULL DEFAULT '',
	signed_pdf_link varchar(255) NOT NULL DEFAULT ''
);
