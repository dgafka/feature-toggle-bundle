# Feature-Toggle-Bundle

#### Description

Feature toggle is responsible for `switching service injection depending on configuration`.
Allowing to modify system behavior without changing code.
 

### Example

Example use [JMSDiExtraBundle](https://github.com/schmittjoh/JMSDiExtraBundle), but you can
easily configure it with `services.yml`

Let's say we want to store images. On developer machines we want to store them on file system,
but anywhere else we want to store them on amazon. 


    interface PictureStorage
    {
        /**
         * @param PictureWithContent $picture
         * @return void
         */
        public function store(string $pictureName, string $content);
    }
   

    /**
    * @DI\Service("amazon_picture_storage")
    * @DI\Tag(name="toggle", attributes={"for"="picture_storage", "on"="amazon_storage", "when"="enabled"})
    */
    class AmazonPictureStorage
    {
        public function store(string $pictureName, string $content)
        {
            // send it amazon
        }
    }

    /**
    * @DI\Service("file_system_picture_storage")
    * @DI\Tag(name="toggle", attributes={"for"="picture_storage", "on"="amazon_storage", "when"="disabled"})
    */
    class FileSystemPictureStorage
    {
        public function store(string $pictureName, string $content)
        {
            // send it amazon
        }
    }
 
Now when we have our services defined. We have to set default service, that will always be
chosen, if all `when` specification fails.
We are doing it by `service aliases`. The default for us is `amazon_picture_storage` 

    services.yml
        picture_storage:
           alias: amazon_picture_storage
    
We should also have the parameter, that we are using for deciding which implementation to pick.   
In our example it's `amazon_storage`. 

    parameters.yml
        amazon_storage: "disabled"



We can make use of our storage now.

    class AppController
    {
        private $pictureStorage;

        /**
         * @DI\InjectParams({
         *      "pictureStorage" = @DI\Inject("picture_storage")
         * })
        */
        public function __construct(PictureStorage $pictureStorage)
        {
            $this->pictureStorage = $pictureStorage;       
        }
    
        public function createPersonWithImage(string $pictureName, string $content)
        {
            $this->pictureStorage->store($pictureName, $content);
        }
    }
    
    
###### Annotation Explained
 
`@DI\Tag(name="toggle", attributes={"for"="picture_storage", "on"="amazon_storage", "when"="disabled"})`

* `name="toggle"` - Marks service `to be used` of FeatureToggleBundle
* `"for"="picture_storage"` - For which service alias, it should be take under consideration
* `"on"="amazon_storage"` - Parameter name, that will be used for choose decision
* `"when"="disabled"` - On what parameter value it should be picked

So service that contains above tag will be used when `amazon_storage` parameters will be `equal to "disabled"`