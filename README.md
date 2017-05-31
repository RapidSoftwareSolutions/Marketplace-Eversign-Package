[![](https://scdn.rapidapi.com/RapidAPI_banner.png)](https://rapidapi.com/package/Eversign/functions?utm_source=RapidAPIGitHub_EversignFunctions&utm_medium=button&utm_content=RapidAPI_GitHub)# Eversign Package

* Domain: [eversign.com](https://eversign.com)
* Credentials: accessKey

## How to get credentials: 
1. Get accessKey https://{{your-company}}.eversign.com/developer
 
`DatePicker` - DateTime format like "YYYY-MM-DD HH:MM:SS"
`Select` -> String format with predefined options
 
## Eversign.getBusinesses
A list of existing businesses on your eversign account

| Field    | Type       | Description
|----------|------------|----------
| accessKey| credentials| Access Key

## Eversign.getDocuments
A list of existing documents

| Field     | Type       | Description
|-----------|------------|----------
| accessKey | credentials| Access Key
| businessId| Number     | Business ID
| type      | Select     | Document status. This parameter accepts the following status values: all, my_action_required, waiting_for_others, completed, drafts, cancelled. And templates variables: templates, templates_archived, template_drafts

## Eversign.getSingleDocument
Get an existing document or template

| Field       | Type       | Description
|-------------|------------|----------
| accessKey   | credentials| Access Key
| businessId  | Number     | Business ID
| documentHash| String     | Document hash (id)

## Eversign.createDocument
Create documentю
JSON structure you can see at [createDocument vendor API](https://eversign.com/api/documentation/methods#create-document)

| Field            | Type       | Description
|------------------|------------|----------
| accessKey        | credentials| Access Key
| businessId       | Number     | Business ID
| isDraft          | Boolean    | Set to 1 in order to save this document as a draft. Default: false
| title            | String     | This parameter is used in order to specify a document title. Default: -
| message          | String     | This parameter is used in order to specify a document message. Default: -
| useSignerOrder   | Boolean    | Set to 1 in order to enable Signing Order for this document. Default: false
| reminders        | Boolean    | Set to 1 in order to enable Auto Reminders for this document. Default: false
| requireAllSigners| Boolean    | Set to 1 in order to require all signers to sign to complete this document. Default: false
| redirect         | String     | This parameter is used to specify a custom completion redirect URL. If empty, the default Post-Sign Completion URL specified in your eversign Business or the eversign signature completion page will be used.
| client           | String     | This parameter is used to specify an internal reference for your application, such as an identification string of the server or client making the API request.
| expires          | DatePicker | This parameter is used to specify a custom expiration date for your document. (Required format: Unix Time Stamp) If empty, the default document expiration period specified in your business settings will be used.
| files            | Array      | Document files can be uploaded to your document either by providing a URL, a reference to an existing file ID or through a base64 string. This object can contain multiple sub arrays. Example: `[{"name": "test file", "fileId": "asd7"}, {"name": "test file2", "fileUrl": "example.com"}, {"name": "test file3", "fileBase64": "base64-text"}]`. Name and one of (fileUrl, fileId, fileBase64) is required fields. 
| signers          | Array      | This object must contain a sub array for each signer of the document being created. Each sub array requires a unique ID, name and email address. Array structure has keys: id*, name*, email*, order, pin, message (* - required). Id - you can set from 1 to X
| recipients       | Array      | This object can contain a sub array for each CC of the document to be signed. Structure: name, email (both required)
| meta             | Array      | This object contains optional key-value data that should be attached to the document. This data will be included when making a GET call for the document created. Structure: key* and value*.
| fields           | Array      | For more information see at [vendor documentation](https://eversign.com/api/documentation/fields) 

## Eversign.useTemplate
Use template
JSON structure you can see at [useTemplate vendor API](https://eversign.com/api/documentation/methods#use-template)

| Field     | Type       | Description
|-----------|------------|----------
| accessKey | credentials| Access Key
| businessId| Number     | Business ID
| templateId| String     | Set to the Template ID of the template you would like to use.
| title     | String     | This parameter is used in order to specify a document title.
| message   | String     | This parameter is used in order to specify a document message.
| redirect  | String     | This parameter is used to specify a custom completion redirect URL. If empty, the default Post-Sign Completion URL specified in your eversign Business or the eversign signature completion page will be used.
| client    | String     | This parameter is used to specify an internal reference for your application, such as an identification string of the server or client making the API request.
| expires   | DatePicker | This parameter is used to specify a custom expiration date for your document. (Required format: Unix Time Stamp) If empty, the default document expiration period specified in your business settings will be used.
| signers   | Array      | This object must contain a sub array for each signing role of your template. Structure: role*, name, email, pin, message
| recipients| Array      | This object can contain a sub array for each CC of the document to be signed. role, name and email required in each object in this array
| fields    | Array      | Array of fields with values `[{"key1": "value1}, {}]`

## Eversign.cancelDocument
Cancel document

| Field       | Type       | Description
|-------------|------------|----------
| accessKey   | credentials| Access Key
| businessId  | Number     | Business ID
| documentHash| String     | Document hash (id)

## Eversign.deleteDocument
Delete document by hash. Only status cancelled or draft

| Field       | Type       | Description
|-------------|------------|----------
| accessKey   | credentials| Access Key
| businessId  | Number     | Business ID
| documentHash| String     | Document hash (id)

## Eversign.downloadOriginalPDF
Download original PDF file

| Field       | Type       | Description
|-------------|------------|----------
| accessKey   | credentials| Access Key
| businessId  | Number     | Business ID
| documentHash| String     | Document hash (id)

## Eversign.downloadFinalPDF
Download final PDF file

| Field       | Type       | Description
|-------------|------------|----------
| accessKey   | credentials| Access Key
| businessId  | Number     | Business ID
| documentHash| String     | Document hash (id)

## Eversign.uploadFile
Upload file

| Field     | Type       | Description
|-----------|------------|----------
| accessKey | credentials| Access Key
| businessId| Number     | Business ID
| file      | File       | File to upload

## Eversign.sendReminder
A reminder can be sent on a per-signer basis only.

| Field       | Type       | Description
|-------------|------------|----------
| accessKey   | credentials| Access Key
| businessId  | Number     | Business ID
| documentHash| String     | Document hash (id)
| signerId    | Number     | Signer ID

