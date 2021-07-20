<?php
/**
 * CompleteFormView Registration
 *
 * @version    1.0
 * @package    clientes
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class CompleteFormView extends TPage
{
    private $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_City');
        $this->form->setClientValidation(true);
        $this->form->setFormTitle(_t('Manual form'));
        
        // create the form fields
        $id       = new TEntry('id');
        $name     = new TEntry('name'); 
        $comente    = new TText('comente');
        $birthdate   = new TDate('birthdate');
        $id->setEditable(FALSE);
        
            // define the sizes
        $id->setSize(40);
          $name->setSize(250);
   
        $comente->setSize(200, 200);
        $birthdate->setSize(200, 200);

        
        // add the form fields
        $this->form->addFields( [new TLabel('ID')],    [$id] );
        $this->form->addFields( [new TLabel('Name', 'red')],  [$name] );
          $this->form->addFields( [new TLabel('Comente', 'red')],  [$comente] );
                    $this->form->addFields( [new TLabel('Data do aniversario', 'red')],  [$birthdate] );

        
        
        $name->addValidation('Name', new TRequiredValidator);
             $comente->addValidation('comente', new TRequiredValidator);
        $birthdate->addValidation('Aniversario', new TRequiredValidator);
        
        // define the form action
        $this->form->addAction('Save', new TAction([$this, 'onSave']), 'fa:save green');
        $this->form->addActionLink('Clear',  new TAction([$this, 'onClear']), 'fa:eraser red');
        $this->form->addActionLink('Listing',  new TAction(['CompleteDataGridView', 'onReload']), 'fa:table blue');
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);

        parent::add($vbox);
    }

    /**
     * method onSave()
     * Executed whenever the user clicks at the save button
     */
    function onSave()
    {
        try
        {
            // open a transaction with database 'clientes'
            TTransaction::open('clientes');
            
            $this->form->validate(); // run form validation
            
            $data = $this->form->getData(); // get form data as array
            
            $object = new City;  // create an empty object
            $object->fromArray( (array) $data); // load the object with data
            $object->store(); // save the object
            
            // fill the form with the active record data
            $this->form->setData($object);
            
            TTransaction::close();  // close the transaction
            
            // shows the success message
            new TMessage('info', 'Record saved');
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Clear form
     */
    public function onClear()
    {
        $this->form->clear( TRUE );
    }
    
    /**
     * method onEdit()
     * Executed whenever the user clicks at the edit button da datagrid
     */
    function onEdit($param)
    {
        try
        {
            if (isset($param['id']))
            {
                $key = $param['id'];  // get the parameter
                TTransaction::open('clientes');   // open a transaction with database 'clientes'
                $object = new City($key);        // instantiates object City
                $this->form->setData($object);   // fill the form with the active record data
                TTransaction::close();           // close the transaction
            }
            else
            {
                $this->form->clear( true );
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
}
