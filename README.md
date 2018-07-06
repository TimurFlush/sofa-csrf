# Sofa Csrf
The library is designed to be soft csrf protection in Phalcon application.

## Attention please
The token is permanent within the current session.
Even if the token is entered incorrectly, it is not updated within the current session.

## Using
```
//in Dependency Injection
$di->setShared(
    'csrf',
    function() {
        //The variable must store the value of interface \Phalcon\Session\AdapterInterface
        $sessionAdapter = $this->getSession();
        
        return new \TimurFlush\SofaCsrf\Protection($sessionAdapter);
    }
);

//in form
class form extends \Phalcon\Form
{
    public function initialize()
    {
        $csrf = new Hidden('csrf_token');
        $csrf->addValidator(
            new \TimurFlush\SofaCsrf\Validator\Csrf(
                [
                    'message' => 'Invalid csrf. Try again!'
                    //option 'cancelOnFail' is already installed
                ]
            )
        );
    }
}
```

## License
BSD-3-Clause

## Version
2.0.0-B1

## Author
Timur Flush 

E-mail: flush02@tutanota.com

Telegram: @flush02