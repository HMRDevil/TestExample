<?php

namespace App\Controller\Api;

use App\Service\User\Dto\Create;
use App\Service\User\Dto\Update;
use App\Service\User\Sex;
use App\Service\User\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/user')]
class UserController extends AbstractController
{
    private const USER_NOT_FOUND = 'User not found';

    public function __construct(
        private UserManager $manager
    ) {}

    #[Route('/all', name: 'all_users', methods: ['GET', 'POST'])]
    public function all(): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $this->manager->all() ?? null
        ]);
    }

    #[Route('/get/{id}', name: 'get_user', methods: ['GET'])]
    public function get(int $id): Response
    {
        if (!$user = $this->manager->getById($id)) {
            throw $this->createNotFoundException(self::USER_NOT_FOUND);
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit_user', methods: ['GET'])]
    public function edit(int $id): Response
    {
        if (!$user = $this->manager->getById($id)) {
            throw $this->createNotFoundException(self::USER_NOT_FOUND);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'sexOptions' => Sex::valuesAsArray()
        ]);
    }

    #[Route('/update', name: 'update_user', methods: ['POST'])]
    public function update(Request $request, #[MapRequestPayload] Update $dto): Response
    {
        if (!$user = $this->manager->getById($dto->id)) {
            throw $this->createNotFoundException(self::USER_NOT_FOUND);
        }

        if (!$this->isCsrfTokenValid('update', $request->request->get('_token'))) {
            $this->addFlash('error', 'You do not have the rights to perform this action');
            return $this->redirectToRoute('edit_user', ['id' => $dto->id]);
        }

        $notice = sprintf('User %s has been updated', $user->getName());
        $this->manager->update($dto, $user);

        return $this->redirectToRoute('edit_user', ['id' => $dto->id]);
    }

    #[Route('/new', name: 'new_user', methods: ['GET'])]
    public function new(): Response
    {
        return $this->render('user/new.html.twig', [
            'sexOptions' => Sex::valuesAsArray()
        ]);
    }

    #[Route('/new', name: 'create_user', methods: ['POST'])]
    public function create(Request $request, #[MapRequestPayload] Create $dto): Response
    {
        if ($this->isCsrfTokenValid('create', $request->request->get('_token'))) {
            try {
                $user = $this->manager->create($dto);
            } catch (\Exception $e) {
                return $this->render('user/new.html.twig', [
                    'sexOptions' => Sex::valuesAsArray(),
                    'notice' => $e->getMessage()
                ]);
            }
        }

        return $this->redirectToRoute('all_users');
    }

    #[Route('/delete', name: 'delete_user', methods: ['POST'])]
    public function delete(Request $request): Response
    {
        $id = (int) $request->request->get('id');
        if (!$user = $this->manager->getById($id)) {
            throw $this->createNotFoundException(self::USER_NOT_FOUND);
        }

        if (!$this->isCsrfTokenValid('delete', $request->getPayload()->get('_token'))) {
            $this->addFlash('error', 'You do not have the rights to perform this action');
            $this->redirectToRoute('get_user', ['id' => $id]);
        }

        $this->addFlash('notice', sprintf('User %s has been deleted', $user->getName()));
        $this->manager->delete($user);

        return $this->redirectToRoute('all_users');
    }
}
