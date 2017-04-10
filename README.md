W3C HTML Validator Result Retriever
===
Retrieves W3C HTML validator results from W3C API in array format.

Usage
------
Include the class in your project. The first required parameter is the URL in string format
and the second one is optional boolean true or false (true serializes the data, default is false).

#### Making a new object
```
$testSite = new ValidateHtml('http://www.example.com/');
```

#### Getting the errors
```
$testSite->htmlErrors;
```

#### Getting the warnings
```
$testSite->htmlWarnings;
```

#### Getting the errors and warnings
```
$testSite->htmlErrorsAndWarnings;
```

#### Getting the url
```
$testSite->url;
```

#### Getting everything that is received from JSON
```
$testSite->htmlAllData;
```

# W3CHtmlValidator
