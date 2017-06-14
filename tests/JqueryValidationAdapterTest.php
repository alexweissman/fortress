<?php

use PHPUnit\Framework\TestCase;
use UserFrosting\Fortress\RequestSchema\RequestSchemaRepository;
use UserFrosting\Fortress\Adapter\JqueryValidationAdapter;
use UserFrosting\I18n\MessageTranslator;

class JqueryValidationAdapterTest extends TestCase
{
    protected $translator;
    
    public function setUp()
    {
        // Create a message translator        
        $this->translator = new MessageTranslator();
    }

    public function testValidateEmail()
    {
        // Arrange
        $schema = new RequestSchemaRepository([
            'email' => [
                'validators' => [
                    'email' => [
                        'message' => 'Not a valid email address...we think.'
                    ]
                ]
            ]
        ]);

        // Act
        $adapter = new JqueryValidationAdapter($schema, $this->translator);
        $result = $adapter->rules();

        $this->assertEquals([
            'rules' => [
                'email' => [
                    'email' => true
                ]
            ],
            'messages' => [
                'email' => [
                    'email' => 'Not a valid email address...we think.'
                ]
            ]
        ], $result);
    }

    public function testValidateEquals()
    {
        // Arrange
        $schema = new RequestSchemaRepository([
            'voles' => [
                'validators' => [
                    'equals' => [
                        'value' => 8,
                        'caseSensitive' => false,
                        'message' => 'Voles must be equal to {{value}}.'
                    ]
                ]
            ]
        ]);

        // Act
        $adapter = new JqueryValidationAdapter($schema, $this->translator);
        $result = $adapter->rules();

        $this->assertEquals([
            'rules' => [
                'voles' => [
                    'equals' => [
                        'value' => 8,
                        'caseSensitive' => false,
                        'message' => 'Voles must be equal to {{value}}.'
                    ]
                ]
            ],
            'messages' => [
                'voles' => [
                    'equals' => 'Voles must be equal to 8.'
                ]
            ]
        ], $result);
    }

    public function testValidateInteger()
    {
        // Arrange
        $schema = new RequestSchemaRepository([
            'voles' => [
                'validators' => [
                    'integer' => [
                        'message' => 'Voles must be numeric.'
                    ]
                ]
            ]
        ]);

        // Act
        $adapter = new JqueryValidationAdapter($schema, $this->translator);
        $result = $adapter->rules();

        $this->assertEquals([
            'rules' => [
                'voles' => [
                    'digits' => true
                ]
            ],
            'messages' => [
                'voles' => [
                    'digits' => 'Voles must be numeric.'
                ]
            ]
        ], $result);
    }

    public function testValidateLengthBetween()
    {
        // Arrange
        $schema = new RequestSchemaRepository([
            'screech' => [
                'validators' => [
                    'length' => [
                        'min' => 5,
                        'max' => 10,
                        'message' => "Your screech must be between {{min}} and {{max}} characters long."
                    ]
                ]
            ]
        ]);

        // Act
        $adapter = new JqueryValidationAdapter($schema, $this->translator);
        $result = $adapter->rules();

        $this->assertEquals([
            'rules' => [
                'screech' => [
                    'rangelength' => [5, 10]
                ]
            ],
            'messages' => [
                'screech' => [
                    'rangelength' => "Your screech must be between 5 and 10 characters long."
                ]
            ]
        ], $result);
    }

    public function testValidateLengthMin()
    {
        // Arrange
        $schema = new RequestSchemaRepository([
            'screech' => [
                'validators' => [
                    'length' => [
                        'min' => 5,
                        'message' => "Your screech must be at least {{min}} characters long."
                    ]
                ]
            ]
        ]);

        // Act
        $adapter = new JqueryValidationAdapter($schema, $this->translator);
        $result = $adapter->rules();

        $this->assertEquals([
            'rules' => [
                'screech' => [
                    'minlength' => 5
                ]
            ],
            'messages' => [
                'screech' => [
                    'minlength' => "Your screech must be at least 5 characters long."
                ]
            ]
        ], $result);
    }

    public function testValidateLengthMax()
    {
        // Arrange
        $schema = new RequestSchemaRepository([
            'screech' => [
                'validators' => [
                    'length' => [
                        'max' => 10,
                        'message' => "Your screech must be no more than {{max}} characters long."
                    ]
                ]
            ]
        ]);

        // Act
        $adapter = new JqueryValidationAdapter($schema, $this->translator);
        $result = $adapter->rules();

        $this->assertEquals([
            'rules' => [
                'screech' => [
                    'maxlength' => 10
                ]
            ],
            'messages' => [
                'screech' => [
                    'maxlength' => "Your screech must be no more than 10 characters long."
                ]
            ]
        ], $result);
    }

    public function testValidateMatches()
    {
        // Arrange
        $schema = new RequestSchemaRepository([
            'password' => [
                'validators' => [
                    'matches' => [
                        'field' => 'passwordc',
                        'message' => "The value of this field does not match the value of the '{{field}}' field."
                    ]
                ]
            ]
        ]);

        // Act
        $adapter = new JqueryValidationAdapter($schema, $this->translator);
        $result = $adapter->rules();

        $this->assertEquals([
            'rules' => [
                'password' => [
                    'matchFormField' => 'passwordc'
                ]
            ],
            'messages' => [
                'password' => [
                    'matchFormField' => "The value of this field does not match the value of the 'passwordc' field."
                ]
            ]
        ], $result);
    }

    public function testValidateMemberOf()
    {
        // Arrange
        $schema = new RequestSchemaRepository([
            'genus' => [
                'validators' => [
                    'member_of' => [
                        'values' => ["Megascops", "Bubo", "Glaucidium", "Tyto", "Athene"],
                        'message' => "Sorry, that is not one of the permitted genuses."
                    ]
                ]
            ]
        ]);

        // Act
        $adapter = new JqueryValidationAdapter($schema, $this->translator);
        $result = $adapter->rules();

        $this->assertEquals([
            'rules' => [
                'genus' => [
                    'memberOf' => ["Megascops", "Bubo", "Glaucidium", "Tyto", "Athene"]
                ]
            ],
            'messages' => [
                'genus' => [
                    'memberOf' => "Sorry, that is not one of the permitted genuses."
                ]
            ]
        ], $result);
    }

    public function testValidateNoLeadingWhitespace()
    {
        // Arrange
        $schema = new RequestSchemaRepository([
            'user_name' => [
                'validators' => [
                    'no_leading_whitespace' => [
                        'message' => "'{{self}}' cannot begin with whitespace characters"
                    ]
                ]
            ]
        ]);

        // Act
        $adapter = new JqueryValidationAdapter($schema, $this->translator);
        $result = $adapter->rules();

        $this->assertEquals([
            'rules' => [
                'user_name' => [
                    'noLeadingWhitespace' => true
                ]
            ],
            'messages' => [
                'user_name' => [
                    'noLeadingWhitespace' => "'user_name' cannot begin with whitespace characters"
                ]
            ]
        ], $result);
    }

    public function testValidateNoTrailingWhitespace()
    {
        // Arrange
        $schema = new RequestSchemaRepository([
            'user_name' => [
                'validators' => [
                    'no_trailing_whitespace' => [
                        'message' => "'{{self}}' cannot end with whitespace characters"
                    ]
                ]
            ]
        ]);

        // Act
        $adapter = new JqueryValidationAdapter($schema, $this->translator);
        $result = $adapter->rules();

        $this->assertEquals([
            'rules' => [
                'user_name' => [
                    'noTrailingWhitespace' => true
                ]
            ],
            'messages' => [
                'user_name' => [
                    'noTrailingWhitespace' => "'user_name' cannot end with whitespace characters"
                ]
            ]
        ], $result);
    }

    public function testValidateNotEquals()
    {
        // Arrange
        // TODO: Add missing messages for custom rules.  Maybe upgrade the version of Valitron first.
        $schema = new RequestSchemaRepository([
            'voles' => [
                'validators' => [
                    'not_equals' => [
                        'value' => 0,
                        'caseSensitive' => false,
                        'message' => 'Voles must not be equal to {{value}}.'
                    ]
                ]
            ]
        ]);

        // Act
        $adapter = new JqueryValidationAdapter($schema, $this->translator);
        $result = $adapter->rules();

        $this->assertEquals([
            'rules' => [
                'voles' => [
                    'notEquals' => [
                        'value' => 0,
                        'caseSensitive' => false,
                        'message' => 'Voles must not be equal to {{value}}.'
                    ]
                ]
            ],
            'messages' => [
                'voles' => [
                    'notEquals' => 'Voles must not be equal to 0.'
                ]
            ]
        ], $result);
    }

    public function testValidateNotMatches()
    {
        // Arrange
        $schema = new RequestSchemaRepository([
            'password' => [
                'validators' => [
                    'not_matches' => [
                        'field' => 'user_name',
                        'message' => "Your password cannot be the same as your username."
                    ]
                ]
            ]
        ]);

        // Act
        $adapter = new JqueryValidationAdapter($schema, $this->translator);
        $result = $adapter->rules();

        $this->assertEquals([
            'rules' => [
                'password' => [
                    'notMatchFormField' => 'user_name'
                ]
            ],
            'messages' => [
                'password' => [
                    'notMatchFormField' => "Your password cannot be the same as your username."
                ]
            ]
        ], $result);
    }

    public function testValidateNotMemberOf()
    {
        // Arrange
        $schema = new RequestSchemaRepository([
            'genus' => [
                'validators' => [
                    'not_member_of' => [
                        'values' => ["Myodes", "Microtus", "Neodon", "Alticola"],
                        'message' => "Sorry, it would appear that you are not an owl."
                    ]
                ]
            ]
        ]);

        // Act
        $adapter = new JqueryValidationAdapter($schema, $this->translator);
        $result = $adapter->rules();

        $this->assertEquals([
            'rules' => [
                'genus' => [
                    'notMemberOf' => ["Myodes", "Microtus", "Neodon", "Alticola"]
                ]
            ],
            'messages' => [
                'genus' => [
                    'notMemberOf' => "Sorry, it would appear that you are not an owl."
                ]
            ]
        ], $result);
    }

    public function testValidateNumeric()
    {
        // Arrange
        $schema = new RequestSchemaRepository([
            'accuracy' => [
                'validators' => [
                    'numeric' => [
                        'message' => "Sorry, your strike accuracy must be a number."
                    ]
                ]
            ]
        ]);

        // Act
        $adapter = new JqueryValidationAdapter($schema, $this->translator);
        $result = $adapter->rules();

        $this->assertEquals([
            'rules' => [
                'accuracy' => [
                    'number' => true
                ]
            ],
            'messages' => [
                'accuracy' => [
                    'number' => "Sorry, your strike accuracy must be a number."
                ]
            ]
        ], $result);
    }

    public function testValidateRange()
    {
        // Arrange
        $schema = new RequestSchemaRepository([
            'voles' => [
                'validators' => [
                    'range' => [
                        'min' => 5,
                        'max' => 10,
                        'message' => "You must catch {{min}} - {{max}} voles."
                    ]
                ]
            ]
        ]);

        // Act
        $adapter = new JqueryValidationAdapter($schema, $this->translator);
        $result = $adapter->rules();

        $this->assertEquals([
            'rules' => [
                'voles' => [
                    'range' => [5, 10]
                ]
            ],
            'messages' => [
                'voles' => [
                    'range' => "You must catch 5 - 10 voles."
                ]
            ]
        ], $result);
    }

    public function testValidateMin()
    {
        // Arrange
        $schema = new RequestSchemaRepository([
            'voles' => [
                'validators' => [
                    'range' => [
                        'min' => 5,
                        'message' => "You must catch at least {{min}} voles."
                    ]
                ]
            ]
        ]);

        // Act
        $adapter = new JqueryValidationAdapter($schema, $this->translator);
        $result = $adapter->rules();

        $this->assertEquals([
            'rules' => [
                'voles' => [
                    'min' => 5
                ]
            ],
            'messages' => [
                'voles' => [
                    'min' => "You must catch at least 5 voles."
                ]
            ]
        ], $result);
    }

    public function testValidateMax()
    {
        // Arrange
        $schema = new RequestSchemaRepository([
            'voles' => [
                'validators' => [
                    'range' => [
                        'max' => 10,
                        'message' => "You must catch no more than {{max}} voles."
                    ]
                ]
            ]
        ]);

        // Act
        $adapter = new JqueryValidationAdapter($schema, $this->translator);
        $result = $adapter->rules();

        $this->assertEquals([
            'rules' => [
                'voles' => [
                    'max' => 10
                ]
            ],
            'messages' => [
                'voles' => [
                    'max' => "You must catch no more than 10 voles."
                ]
            ]
        ], $result);
    }

    public function testValidateRegex()
    {
        // Arrange
        $schema = new RequestSchemaRepository([
            'screech' => [
                'validators' => [
                    'regex' => [
                        'regex' => "^who(o*)$",
                        'message' => "You did not provide a valid screech."
                    ]
                ]
            ]
        ]);

        // Act
        $adapter = new JqueryValidationAdapter($schema, $this->translator);
        $result = $adapter->rules();

        $this->assertEquals([
            'rules' => [
                'screech' => [
                    'pattern' => "^who(o*)$"
                ]
            ],
            'messages' => [
                'screech' => [
                    'pattern' => "You did not provide a valid screech."
                ]
            ]
        ], $result);
    }

    public function testValidateRequired()
    {
        // Arrange
        $schema = new RequestSchemaRepository([
            'species' => [
                'validators' => [
                    'required' => [
                        'message' => "Please tell us your species."
                    ]
                ]
            ]
        ]);

        // Act
        $adapter = new JqueryValidationAdapter($schema, $this->translator);
        $result = $adapter->rules();

        $this->assertEquals([
            'rules' => [
                'species' => [
                    'required' => true
                ]
            ],
            'messages' => [
                'species' => [
                    'required' => "Please tell us your species."
                ]
            ]
        ], $result);
    }

    public function testValidateTelephone()
    {
        // Arrange
        $schema = new RequestSchemaRepository([
            'phone' => [
                'validators' => [
                    'telephone' => [
                        'message' => "Whoa there, check your phone number again."
                    ]
                ]
            ]
        ]);

        // Act
        $adapter = new JqueryValidationAdapter($schema, $this->translator);
        $result = $adapter->rules();

        $this->assertEquals([
            'rules' => [
                'phone' => [
                    'phoneUS' => true
                ]
            ],
            'messages' => [
                'phone' => [
                    'phoneUS' => "Whoa there, check your phone number again."
                ]
            ]
        ], $result);
    }

    public function testValidateUri()
    {
        // Arrange
        $schema = new RequestSchemaRepository([
            'website' => [
                'validators' => [
                    'uri' => [
                        'message' => "That's not even a valid URL..."
                    ]
                ]
            ]
        ]);

        // Act
        $adapter = new JqueryValidationAdapter($schema, $this->translator);
        $result = $adapter->rules();

        $this->assertEquals([
            'rules' => [
                'website' => [
                    'url' => true
                ]
            ],
            'messages' => [
                'website' => [
                    'url' => "That's not even a valid URL..."
                ]
            ]
        ], $result);
    }

    public function testValidateUsername()
    {
        // Arrange
        $schema = new RequestSchemaRepository([
            'user_name' => [
                'validators' => [
                    'username' => [
                        'message' => "Sorry buddy, that's not a valid username."
                    ]
                ]
            ]
        ]);

        // Act
        $adapter = new JqueryValidationAdapter($schema, $this->translator);
        $result = $adapter->rules();

        $this->assertEquals([
            'rules' => [
                'user_name' => [
                    'username' => true
                ]
            ],
            'messages' => [
                'user_name' => [
                    'username' => "Sorry buddy, that's not a valid username."
                ]
            ]
        ], $result);
    }


    public function testDomainRulesClientOnly()
    {
        // Arrange
        $schema = new RequestSchemaRepository([
            'plumage' => [
                'validators' => [
                    'required' => [
                        'domain' => 'client',
                        'message' => "Are you sure you don't want to show us your plumage?"
                    ]
                ]
            ]
        ]);

        // Act
        $adapter = new JqueryValidationAdapter($schema, $this->translator);
        $result = $adapter->rules();

        $this->assertEquals([
            'rules' => [
                'plumage' => [
                    'required' => true
                ]
            ],
            'messages' => [
                'plumage' => [
                    'required' => "Are you sure you don't want to show us your plumage?"
                ]
            ]
        ], $result);
    }

    public function testDomainRulesServerOnly()
    {
        // Arrange
        $schema = new RequestSchemaRepository([
            'plumage' => [
                'validators' => [
                    'required' => [
                        'domain' => 'server',
                        'message' => "Are you sure you don't want to show us your plumage?"
                    ]
                ]
            ]
        ]);

        // Act
        $adapter = new JqueryValidationAdapter($schema, $this->translator);
        $result = $adapter->rules();

        $this->assertEquals([
            'rules' => [
                'plumage' => []
            ],
            'messages' => []
        ], $result);
    }
}