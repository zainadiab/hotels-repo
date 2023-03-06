<?php
App::uses('AppController', 'Controller');
App::uses('HttpSocket', 'Network/Http');
use Cake\Http\Client;

class HotelsController extends AppController {

    public function index(){
        $pageTitle = "Explore Jordan";
        $this->set('pageTitle', $pageTitle);

        $this->loadModel('Address');
        $this->loadModel('Room');


        $addressList = $this->Address->find('list',array('fields' =>['id','address']));
        $this->set('addressList',$addressList);

        if ($this->request->is('post')) {
           // pr($this->request->data); die;
            $address = $this->request->data['Hotel']['address_id'];
            $roomType = $this->request->data['Hotel']['room_type'];

            $this->Hotel->virtualFields['address_name'] = 'Address.address';
             $this->Hotel->virtualFields['room_id'] = 'Room.id';
             $this->Hotel->virtualFields['price'] = 'Room.price';

    
            $hotels = $this->Hotel->find('all', array(
                'conditions' => array(
                    'Hotel.address_id' => $address,
                    'Room.type' => $roomType,
                    'Room.reserved' => 0,
                    'Hotel.deleted' => 0
                ),
                'joins' => [
                    [
                        'table'      => 'rooms',
                                        'alias'      => 'Room',
                                        'type'       => 'inner',
                                        'foreignKey' => false,
                                        'conditions' => array('Room.hotel_id = Hotel.id'),
                    ],
                    [
                        'table' => 'addresses',
                        'alias' => 'Address',
                        'type' => 'inner',
                        'conditions' => ['Hotel.address_id = Address.id']
                    ]
                ]
            ));
           
            // Pass the list of hotels to the view
            $this->set('hotels', $hotels);
        }

    }
    
        
        public function view(){
          
            $owner_id = $this->request->query['owner_id'];
            
            $this->loadModel('Hotel');
            
            $this->Hotel->virtualFields['address_name'] = 'Address.address';
            $hotels = $this->Hotel->find('all', array(
                        'conditions' => array('Hotel.owner_id' => $owner_id,'Hotel.deleted' => 0),
                        'joins' => [
                            [
                                'table' => 'addresses',
                                'alias' => 'Address',
                                'type' => 'inner',
                                'conditions' => ['Hotel.address_id = Address.id']
                            ]
                        ]
                   ));
                  
                   if($hotels){
                    $this->autoRender = false; // disable rendering a view
                    $this->response->type('json'); // set response type to JSON
                    $this->response->body(json_encode($hotels)); // set response body to JSON-encoded hotels
                    return $this->response;
                }else{
                    return "no_data_found";
                }
        }

        public function add($owner_id = null) {
            $data = $this->request->query;
            $this->loadModel('Address');

            $addressList = $this->Address->find('list',array('fields' =>['id','address']));
            $this->set('addressList',$addressList);
            if ($this->request->is('post')) {
                
                $this->Hotel->create();
                if ($this->Hotel->save($this->request->data)) {
                    
                    $this->Flash->success(__('The hotel has been saved.'));
                    $this->redirect('http://users/users/index?owner_id='. $data['owner_id']);
                } else {
                    $this->Flash->error(__('The hotel could not be saved. Please, try again.'));
                }
            }
            $this->set('owner_id',$data['owner_id']);
            $this->render('add');
        }
        
    public function edit(){
        $data = $this->request->query;
        $this->loadModel('Address');
        $id = $data['id'];
        $this->Hotel->virtualFields['address_name'] = 'Address.address';

        $hotel = $this->Hotel->find('all', array(
            'conditions' => array('Hotel.id' => $id),
            'joins' => [
                [
                    'table' => 'addresses',
                    'alias' => 'Address',
                    'type' => 'inner',
                    'conditions' => ['Hotel.address_id = Address.id']
                ]
            ]
       ));
       $addressList = $this->Address->find('list',array('fields' =>['id','address']));
        $this->set('addressList',$addressList);
       $this->set('hotel',$hotel);
       $this->set('id', $id);
       
        if($this->request->is('post') || $this->request->is('put')) {
          
            $this->Hotel->id = $id;
            
            if($this->Hotel->save($this->request->data)) {
                $data2 = $this->request->data;
                $this->Flash->success(__('The hotel has been saved.'));
                $this->redirect('http://users/users/index?owner_id=' . $data2['Hotel']['owner_id']);
            }
            else {
                $this->Flash->error(__('Unable to update the hotel.'));
            }
        }
        else {
            $this->request->data = $hotel;
        }

        $this->set(compact('hotel'));

        $this->render('edit');
        }
    
        public function delete()
        {
            $this->autoRender = false;
            $data = $this->request->query;
                $this->id = $data['id'];
                
                if($this->Hotel->updateAll(
                    array('Hotel.deleted' => 1),
                    array('Hotel.id' => $data['id'])
                )){
                   // $this->Flash->success(__('The hotel has been deleted.'));
                    return $this->redirect('http://users/users/index?owner_id=' . $data['owner']);
                }
        }


        public function reserve(){
            $this->autoRneder = false;
            pr($this->request->query); die;
        }
       
}