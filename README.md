W3C HTML validator data retriever
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

#### Gettting the errors
```
$testSite->htmlErrors;
```

#### Gettting the warnings
```
$testSite->htmlWarnings;
```

#### Gettting the errors and warnings both
```
$testSite->htmlErrorsAndWarnings;
```

#### Gettting the url
```
$testSite->url;
```

#### Getting everything that is received with JSON
```
$testSite->htmlAllData;
```

# W3CHtmlValidator
