<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29.10.18
 * Time: 23:44
 */

namespace App\Controllers\Api;

use App\Entities\SmsSending;
use App\Entities\User;
use App\Http\Request;
use App\Http\Response;
use App\Repositories\SmsSendingRepository;
use App\Repositories\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Rakit\Validation\Validator;
use Src\Facades\Bcrypt;
use Src\Facades\JWTToken;

class SmsSendingController extends ApiBaseController
{
    /**
     * @var EntityManager|mixed
     */
    private $manager;

    /**
     * @var SmsSendingRepository
     */
    private $entityRepository;

    /**
     * UserController constructor.
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);

        $this->manager = container()->get(EntityManager::class);
        $this->entityRepository = $this->manager->getRepository(SmsSending::class);

    }

    /**
     * @return Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function index()
    {
        try {
            $entities = $this->entityRepository
                ->pagination($this->request->get('currentPage'), $this->request->get('perPage'))
                ->getResult();

            $total = $this->entityRepository
                ->findTotal()
                ->getTotal();
        } catch (NoResultException  | NonUniqueResultException $e) {
            return $this->json(['success' => false, 'error' => ['No result founded']], 400);
        }


        $data = [];
        foreach ($entities as $item) {
            $data[] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'text' => $item['text'],
                'status' => $item['status'],
                'phone' => $item['phone'],
                'created_at' => $item['created_at']->format('Y-m-d H:i:s'),
            ];
        }

        $result = [
            'items' => $data,
            'perPage' => (int)$this->request->get('perPage'),
            'currentPage' => (int)$this->request->get('currentPage'),
            'total' => (int)$total,
        ];

        return $this->json(['success' => true, 'data' => $result]);
    }

    /**
     * @param $id
     * @return \App\Http\Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function show($id)
    {
        $manager = container()->get(EntityManager::class);
        $qb = $manager->createQueryBuilder()
            ->select(['s.id', 's.name', 's.text', 's.phones'])
            ->from(SmsSending::class, 's')
            ->where('s.id = :id')
            ->setParameter('id', $id);

        $user = $qb->getQuery()->getSingleResult();

        if (!empty($user)) {

            $data = [
                'id' => $user['id'],
                'name' => $user['name'],
                'phones' => $user['phones'],
                'text' => $user['text'],
            ];


            return $this->json(['success' => true, 'data' => $data]);
        }


        return $this->json(['success' => false], 404);
    }

    /**
     * @return \App\Http\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function store()
    {
        $manager = container()->get(EntityManager::class);
        $entity = new SmsSending();

        $validator = new Validator();
        $validation = $validator->make($this->request->all(), [
            'name' => 'required|min:4|max:100',
            'text' => 'required|min:4|max:100',
            'phone' => 'required|min:9|max:12'
        ]);
        $validation->validate();

        if ($validation->fails()) {
            return $this->json([
                'success' => false,
                'error' => $validation->errors()->toArray()
            ], 400);
        }

        $entity->setName($this->request->get('name'));
        $entity->setText($this->request->get('text'));
        $entity->setPhone($this->request->get('phone'));
        $entity->setDefaultStatus();
        $entity->setCreatedAt(new \DateTime());

        $manager->persist($entity);
        $manager->flush();

        if ($entity->getId()) {
            return $this->json(['success' => true, 'data' => [
                'id' => $entity->getId(),
                'name' => $entity->getName(),
                'text' => $entity->getText(),
                'phone' => $entity->getPhone(),
                'created_at' => $entity->getCreatedAt()
            ]]);
        }

        return $this->json(['success' => false, 'error' => 'Entity didnt created'], 400);
    }

    /**
     * @param $id
     * @return \App\Http\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function update($id)
    {
        $manager = container()->get(EntityManager::class);
        $user = $manager->getRepository(User::class)->find($id);


        if (empty($user)) {
            return $this->json(['success' => false, 'error' => ['Not Found']], 404);
        }

        if (!empty($this->request->get('name'))) {

            $user->setName($this->request->get('name'));
            $manager->persist($user);
            $manager->flush();

            return $this->json(['success' => true]);
        }

        return $this->json(['success' => false, 'error' => ['name' => 'required']], 404);

    }

    /**
     * @param $id
     * @return \App\Http\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function delete($id)
    {
        $manager = container()->get(EntityManager::class);
        $entity = $manager->getRepository(SmsSending::class)->find($id);

        if (!empty($entity)) {
            $entity->setDeletedAt(new \DateTime());
            $manager->persist($entity);
            $manager->flush();

            return $this->json(['success' => true]);


        }
        return $this->json(['success' => false, 'errors' => ['entity' => ['entity' => 'Not found']]], 400);
    }


}
