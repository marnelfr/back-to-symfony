## API Platform
Installation: ``composer req api``

To make an entity, an api resource, just add ``#[ApiResource]`` of
top of the entity.

To display the data send by an api point, add the format as extension
to the link otherwise, api_platform show those data through its
doc.\
e.g.: ``https://localhost/api/cheese_listings/2.jsonld``

## OpenAPI
The Swagger doc generated by api_platform (ap) is generated thanks to 
OpenAPI. We can even display the OpenAPI code generated by ap via
the link\
``https://localhost/api/docs.json`` \
It's even possible to generate the frontend js code that can 
communicate with our api using **Swagger Codegen**, 
some tools provided by swagger.

## RDF
Stand for **Resource Description Framework**. It's a set of rules about 
how we can describe the meaning of data.
JsonLD add some extra fields to our json in order to help machine 
understanding our API.

### @id
In a Rest API, every URL represent a resource and should have its 
own identifier. That's why thanks to JsonLD, every resource has 
the ``@id`` filed representing when concatenate to our API
domain name, the unique identifier of our resource across the 
entire internet: it's the **IRI** (Internationalized 
Resource Identifier).

### @context
Provide a link to machine to really understand our resource. In our
case, the complete url will be\
``https://localhost:8000/api/contexts/CheeseListing`` \
Here, every think is listed to help machine understanding really
what our resource actually represent. We can even make it more
informational by providing description for our entities properties.

### @type
A resume from information provided by @context that let machine 
know about our resource's type.


## Profiler
Installation: ``composer req profiler --dev``\
While we can install the debugger ``req debug`` to get an extra 
debug tools in addition to the profiler, it can be installed alone.

### Operations
We've got 2 type of operations:
- **collectionOperation**: doesn't require a resource's Id. It has
  - a GET method: to retrieve a collection of resource
  - a POST method: to create a collection of resource
- **itemOperation**: require a resource Id and has
  - a GET method
  - a PUT method: to edit the whole resource
  - a PATCH method: to only edit some fields of our resource.
  - a DELETE method: to destroy a resource.

So while setting up our resource, we can define which kind or 
operation we want our API to expose:
````php 
#[ApiResource(
    collectionOperations: ['GET', 'POST'],
    itemOperations: ['GET', 'PUT', 'PATCH', 'DELETE'],
)]
````
Removing any of these array value will remove the api endpoint.\
And each of them, we can be customized if transformed into a key
with its config as value:\
``'GET' => ['path' => '/lov/de/{id}']``

## Serializer
It used to convert our resource from object to json before sending
and from json to object before using them in our app.
To do that, it uses the getter and the setter of our entity.
This mean we can 
- change/delete our resources' json properties by modifying
our getters 
- and change/delete the json properties our api will receive by modifying
our setters. 
 



## ApiResource's properties
- shortName: 'Cheese': to rename our resource





