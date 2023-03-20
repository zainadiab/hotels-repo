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
                    $this->autoRender = false; 
                    $this->response->type('json'); 
                    $this->response->body(json_encode($hotels));
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
                    return $this->redirect('http://users/users/index?owner_id=' . $data['owner']);
                }
        }
        
        public function reserve($room_id = null) {
            $this->loadModel('Reservation');
            $this->loadModel('Room');
            $data = $this->request->query;
            $room_id = $data['id'];
            $this->set('room_id',$room_id);
            if (!$room_id) {
                $this->Flash->error(__('Invalid room'));
            }
        
            if ($this->request->is('post')) {
              
                $data = $this->request->data;
                $this->Reservation->create();
                $data['Reservation']['room_id'] = $room_id;
                if ($this->Reservation->save($data)) {
                    $this->Room->id = $room_id;
                    $this->Room->saveField('reserved', 1);
                    $this->Flash->success(__('The reservation has been saved.'));
                    return $this->redirect(array('action' => 'index'));
                } else {
                    $this->Flash->error(__('The reservation could not be saved. Please, try again.'));
                }
            }
          
            $room = $this->Room->find('first',array('fields' => ['id'], 'conditions' => ['id' => $room_id], 'recursive' => -1));
            if (!$room) {
               return $this->Flash->error(__('Invalid room'));
            }
            $this->set('room', $room);
        
            $this->render('reserve');
        }

        public function viewReservations($hotel_id = null) {

            $data = $this->request->query;
            $hotel_id = $data['id'];
            $this->loadModel('Room');
            $this->loadModel('Reservation');
            $hotel = $this->Hotel->find('all', array(
                'conditions' => array('Hotel.id' => $hotel_id),
            ));
            $hotel_name = $hotel[0]['Hotel']['name'];
            $this->set('hotel_name',$hotel_name);
            $rooms = $this->Room->find('all', array(
                'conditions' => array('Room.hotel_id' => $hotel_id, 'Room.reserved' => 1),
                'recursive' => -1
            ));
            $reservations = $this->Reservation->find('all', array(
                'conditions' => array('Reservation.room_id' => Hash::extract($rooms, '{n}.Room.id')),
                'recursive' => -1
            ));
            $this->set(compact('rooms', 'reservations'));
        }
        
        
       
}