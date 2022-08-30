## Commencement
To see the readme update's history, please refer to the 
first branch of [Api-Platform](https://github.com/marnelfr/back-to-symfony/tree/api-platform)


## API Platform
Installation: ``composer req api``

To make an entity, an api resource, just add ``#[ApiResource]`` on the
top of the entity.

To display the data send by an api endpoint in a specific format (json, jsonld), 
add the format as extension to the link ; otherwise, api_platform
shows those data through the swagger's doc.\
e.g.: [https://localhost/api/cheese_listings/2.jsonld](https://localhost/api/cheese_listings/2.jsonld)

## OpenAPI
The Swagger doc generated by api_platform (ap) is generated thanks to 
OpenAPI. We can even display the OpenAPI code generated by ap via
the link [https://localhost/api/docs.json](https://localhost/api/docs.json)\
It's even possible to generate the frontend js code that can 
communicate with our api using **Swagger Codegen**, 
some tools provided by swagger.

## RDF
Stand for **Resource Description Framework**. It's a set of rules about 
how we can describe the meaning of data.
The RDF JsonLD add some extra fields to our json in order to help machines 
understanding our API.

### @id
In a Rest API, every resource is represented by an URL and should have its 
own identifier. That's why thanks to JsonLD, every resource has 
the ``@id`` filed representing when concatenated to our API
domain name, the unique identifier of our resource across the 
entire internet: it's the **IRI** (Internationalized 
Resource Identifier).

### @context
Provide a link to machine to really understand our resource. In our
case, the complete url will be [https://localhost/api/contexts/CheeseListing](https://localhost/api/contexts/CheeseListing)\
Here, every thing is listed to help machines understanding really
what our resource actually represent. We can even make it more
informational by providing description for our entities properties.


### @type
A resume from information provided by @context that let machines 
know about our resource's type.


## Profiler
Installation: ``composer req profiler --dev``\
While we can install the debugger ``req debug`` to get some extra 
debug tools in addition to the profiler, it can be installed alone.

### Operations
We've got 2 type of operations:
- **collectionOperation**: doesn't require a resource's Id. It has
  - a GET method: to retrieve a collection of resources
  - a POST method: to create a resource
- **itemOperation**: require a resource's Id and has
  - a GET method to get an unique resource based on its id
  - a PUT method: to edit the whole resource
  - a PATCH method: to only edit some fields of our resource.
  - a DELETE method: to destroy the resource.

So while setting up our resources, we can define operations we 
want our API to expose:
````php 
#[ApiResource(
    collectionOperations: ['GET', 'POST'],
    itemOperations: ['GET', 'PUT', 'PATCH', 'DELETE'],
)]
````
Removing any of these array value will remove the api endpoint.\
And each of them can be customized if transformed into a key
with its config as value:\
``'GET' => ['path' => '/lov/de/{id}']``

## Serializer
It used to convert our resources from object to json before sending
and from json to object before using them in our app.
To do that, it uses getters and setters of our entity 
since it uses the ObjectNormalizer that employ the 
**PropertyAccess component**.
This mean we can 
- change/delete our resources' json properties by modifying
our getters 
- and change/delete the json properties our api will receive 
by modifying our setters. 
 
### Serialization groups
The normalization is the process turning our resource into an
array. Only the array got from that process is then convert
into JSON and send to the client. So we can pass the **groups** 
option to our **normalizationContext**. Henceforth, only field 
in these group will be considered in the serialization process.
Same thing can be done with **denormalizationContext**.\
Even getters and setters can be added to a group.
````php 
normalizationContext: [
  'groups' => ['read:cheese'], 
  'swagger_definition_name' => 'Read' // make our doc's schemas section more readable
]
````

### Serialized name
We can set the name through witch our resource's properties
will be set:
```php 
#[SerializedName('textDescription')]
private ?string $description = null;
````

## Constructor args
Removing the setter of a property and adding it as a constructor
arg make it **immutable** (can be set only once). We can make it
in our entities while using AP, but we should make sure that the 
arg has the same name as the concerned property. Also, **making it 
nullable is a good way to prevent a 400 error code when it's not sent**:
````php
public function __construct(string $title = null) {
    $this->title = $title;
}
````

## ApiFilter
A beautiful way to filter our data. It uses a (non-named arg) [Filter class](https://api-platform.com/docs/core/filters/#doctrine-orm-and-mongodb-odm-filters)
and generally the concerned properties:
````php 
#[ApiFilter(BooleanFilter::class, properties: ['isPublished'])]
#[ApiFilter(SearchFilter::class, properties: [
    'title' => 'partial', 
    'description' => 'partial', 
    'owner' => 'exact',
    'owner.username' => 'partial'
])]
#[ApiFilter(RangeFilter::class, properties: ['price'])]
#[ApiFilter(PropertyFilter::class)]
````
Most Filter classes are in the ``Doctrine/Orm`` namespace so 
thanks to PHPStorm, we can even display all of them. Each of 
them has a quick description of how they can be used.\
They can even be related to embedded resource as defined on our
``SearchFilter`` above. We are here applying our search filter to
the username of the embedded resource ``User`` and to the User IRI
also. Yes, it's better to search by the IRI than by the ID.

The **PropertyFilter** however is not in that namespace 
and allow our api client to get only properties 
it needs from those we make available. Using it can lead us to such of
link\
[http://localhost/api/users/2?properties[]=username&properties[cheeseListing]\[\]=title](http://localhost/api/users/2?properties[]=username&properties[cheeseListing][]=title)\
Here, username is our main resource property while title is an embedded resource
property.


## Validation
Validation and API Platform work exactly like in every Symfony app,
and API Platform tacks care of the boring work of mapping serialization
and validation value to a 400 status code and descriptive, consistence
error response.\
We then only need to add **[Validator/Constraints](https://symfony.com/doc/current/validation.html#supported-constraints)** to our classes'
properties and let ap do the remaining work.

## Embedded relation
When we add a read group to a relation property, it only means that
we want to get the IRI of the embedded resource while loading our main 
resource. However, it's possible to preload some of its properties
we need in addition.\

### Read embedded relation's data
To get to that, we only need to add one of the main
entity's read groups to those properties we want to preload.
It's recommended to only preload embedded data when loading a single
resource while we only the IRI of embedded relation when loading a
collection. We then need to have a specific group for each case:
````php 
#[ApiResource(
    itemOperations: [
        'GET' => [
            'normalization_context' => ['groups' => [
                'cheese:read', 'cheese:item:get'
            ]]
        ],
        'PUT', 'PATCH', 'DELETE'
    ],
    denormalizationContext: [
        'groups' => ['cheese:write'],
        'swagger_definition_name' => 'Write'
    ]
)]
````
Since we personalized the ``itemOperations/GET`` saying we want to
read in addition to properties having ``cheese:read`` group, those
having the ``cheese:item:get`` group, we can the simply add this last
group to our embedded relations properties we want to preload. 

### Create/Update embedded relation's data
By this same way, we can also add write group to our embedded relation's
properties we want to be able to write while creating/updating our resource.
However, in case we want to only update an embedded data, we must
send its IRI. Otherwise, we're then trying to create a new embedded 
resource. This could only be possible if **persist cascade** is enabled.\
**If write embedded data is allowed, it's very important to make sure
the embedded relation is validated by adding the ``Valid`` constraint
validator to our relation.**

## Collection create
While creating a resource in a OneToMany relation with another resource,
we can send an array of embedded object to update them when an IRI is
provided xor to create them. In this last case, cascade persist must be
enabled.

## Removing items from a collection
Given a resource related to many others. In a PUT or PATCH operation,
unless we don't specify the embedded relation field, we must send back 
all the related resources. This is because the missing ones will be
detached causing error or removed from the database if ``orphanRemoval``
is set to true.



## Groups' naming convention
- **user:item:get**: can be added to the user:read group to load more 
data when we're loading an unique resource data 
- **user:item:put**: can be defined alone on put operation in order 
to specify the group of properties that can be written on a put operation.
- **user:item:patch**: same principe as for the put group
- **user:read**: stand for user:collection:read; the basic read group
- **user:write**: stand for user:collection:write; the basic write group

## [ApiSubresource]
Applied to a relational attribute, it adds an endpoint to 
get all the subresource. In this case, it's:\
[http://localhost/api/users/{id}/cheese_listing](http://localhost/api/users/{id}/cheese_listing)\
More information about [here](https://api-platform.com/docs/core/subresources/)












## ApiResource's properties
- (shortName: 'Cheese'): to rename our resource
- (paginationItemsPerPage: 2): to paginate the client request
result up to 2 items per page. Brings use the ``hydra:view`` property
that has useful information.
- (paginationPartial: true): makes lighter the ``hydra:view``
returned information
- (formats: ['jsonld', 'html', 'json', 'csv' => ['text/csv']]):
allow use to add as much format as we want. Always add default
ones when we want to add a new one. Since we added the **CSV** format,
we can then download our api response we such of link:
[http://localhost/api/cheeses.csv?page=1](http://localhost/api/cheeses.csv?page=1)


  



