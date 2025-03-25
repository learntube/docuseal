## DocuSeal Extension for TYPO3 CMS

Classification: docuseal

Version: 2.0.0

Language: en, de

Description: This extension allows TYPO3 to integrate with DocuSeal services, enabling frontend users to securely sign documents using the DocuSeal API. It was developed and is maintained by LEARNTUBE GmbH and used for [LMS3](https://www.lms3.de/).

Keywords: DocuSeal, e-Signature, Digital Signature, User Signature, LMS3, E-Learning

Copyright: 2024

Author: Kallol Chakraborty, Stefan Ramaker, LEARNTUBE GmbH

License: This document is published under the Open Content License available from https://www.opencontent.org/opl.shtml

The content of this document is related to TYPO3, a GNU/GPL CMS/Framework available from www.typo3.org.

------------

### About DocuSeal

DocuSeal is an open-source platform that provides a secure and efficient way to digitally sign documents. DocuSeal aligns with the ESIGN Act and the UETA in the US, and the eIDAS regulation in Europe (first level), making signed documents legally binding. For more details, refer to [DocuSeal](https://www.docuseal.co/).

We express our thanks to the DocuSeal team for their effective support throughout the development process.

------------

### Major functionalities of "docuseal" TYPO3 extension
* This extension allows frontend user information to be filled in on the PDF that needs to be signed.
* It provides seamless integration of the signing form.
* The signing form automatically detects and uses the website’s language.
* The URL of the signed PDF is saved in the TYPO3 system within the fe_users table. Refer to the “DocuSeal” tab of the Frontend User TCA for more details.

------------

### Installation and Configuration

The installation process is straightforward and involves the following steps:

1. Run `composer req lms3/docuseal`.

2. Run the Database Analyzer.

3. Configure Extension Settings:
    * Settings > Extension Configuration > docuseal > **Settings**:
        * **Enable DocuSeal Integration** - Disable if you want to turn off the entire DocuSeal integration. By default, it is enabled.
    * Settings > Extension Configuration > docuseal > **Api**:
        * **Base Point of the API** - Obtain from your DocuSeal Application.
        * **Secret Token** - Obtain from your DocuSeal Application.
    * Settings > Extension Configuration > docuseal > **Embedded Signing Form**:
        * **Base Point of Public URL of the DocuSeal Signing Form** - Obtain from your DocuSeal Application.

4. Install the plugin on a standard page.

5. Configure the Flexform of the plugin:
* **Template ID** - This is mandatory and can be obtained from the DocuSeal Console. We presume that you have already created or imported a template inside the DocuSeal console. Additionally, you can create fields for the template.
* **User-Data mapping information to populate the form via API** - This is an optional field. You need to provide the mapping information only if you want the PDF to be filled with data from the fe_users table. Mapping information should follow a strict pattern like feuser_field:docuseal_field, for example, first_name:firstname (assuming that the corresponding field name in the DocuSeal template is firstname). Use a new line for each field.

<pre>
Example:

first_name:firstname
last_name:lastname
email:email
</pre>

* **Redirect to the specified page/URL after the document is signed** - This is an optional field. You can set another TYPO3 page or an external link where the user will be redirected once the signature is made.
* **Custom CSS to override the DocuSeal widget styling** - If you are not satisfied with the default style of the DocuSeal Signing Form, you can override the CSS here. Again, this is an optional field.

<pre>
Example:

#form_container { background-color: #36BAD1; color: #fff; }
#type_text_button, .btn-outline { color: #fff; }
#type_text_button:hover, .btn-outline:hover { background-color: #C2D224; border-color: #C2D224; }
#expand_form_button, #submit_form_button { background-color: #C2D224; color: #575756; border-color: #C2D224; }
#expand_form_button:hover, #submit_form_button:hover { color: #fff }
.tooltip { --tooltip-color: #C2D224; --tooltip-text-color: #575756}
.text-center {color: #575756 }
.base-input {color: #575756 }
</pre>

**Note:** You can obtain an API key directly from DocuSeal website according to their pricing plans, or you can test with a sandbox account.

------------

### Found a bug?

* First, check out the main branch and verify that the issue has not yet been resolved.
* Review the existing [issues](https://github.com/learntube/docuseal/issues) to prevent duplicates
* If not found, report the bug in our [issue tracker](https://github.com/learntube/docuseal/issues/new).

------------

### Like a new feature?

* If you want to **sponsor** a feature, get in [contact](https://www.lms3.de/home/kontakt) with us or email us at mail@learntube.de.
* You can also contact us for any customization needs.
------------

### Want to contribute?

* If you would like to contribute externally, we welcome your pull requests. Please ensure your commit messages start with [BUGFIX] or [FEATURE] and are properly detailed.
