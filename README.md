## API Platform
Installation: ``composer req api``

To make an entity, an api resource, just add ``#[ApiResource]`` of
top of the entity.

To display the data send by an api point, add the format as extension
to the link otherwise, api_platform show those data through its
doc.\
e.g.: ``https://localhost/api/entity/2.jsonld``

