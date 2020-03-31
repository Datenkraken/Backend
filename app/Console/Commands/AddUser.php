<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * This class represents the user:add command and
 * is responsible for creating a user interactively via console.
 *
 * @author Tobias Kröll - tobias.kroell@stud.tu-darmstadt.de
 */
class AddUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user interactively.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Creating a new user...');
        $user = null;

        while ($user === null) {
            $user = $this->tryUserCreation();
        }

        $this->info('User created successfully');
    }

    /**
     * This will ask the user for all needed input and will try
     * to validate the input and create a user when successfull.
     * The user will be returned or null, if there was an error.
     * Errors will be printed to the console.
     *
     * @return User|null
     */
    private function tryUserCreation()
    {
        $user = null;
        $data = [];

        $data['email'] = $this->ask('What is the email address?');
        $data['password'] = $this->secret('What is the password?');
        $data['password_confirmation'] = $this->secret('Please enter the password again');
        $data['is_admin'] = $this->confirm('Should the user be an admin?');

        $validated = $this->validateInput($data);

        if ($validated === null) {
            $this->line('');
            $this->line('Please retry and enter valid information:');

            return null;
        }

        // Create the user instance
        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Set the is_admin manually, as this is not marked as "fillable"
        // to avoid mass assignment
        $user->is_admin = $data['is_admin'];
        $user->save();

        return $user;
    }

    /**
     * This will validate the given data against the rules of the {@link User} model.
     * It will also output errors to the console.
     * This will return the validated data if successfull and null otherwise.
     *
     * @param array $data The input data.
     *
     * @return array|null The validated data.
     */
    private function validateInput(array $data)
    {
        $validated = null;

        try {
            $validated = Validator::validate($data, User::validationRules());
        } catch (ValidationException $exception) {
            $this->printValidationErrors($exception);
        }

        return $validated;
    }

    /**
     * This will take care of printing all of the validation errors.
     *
     * @param ValidationException $exception The validation exception that happened.
     */
    private function printValidationErrors(ValidationException $exception)
    {
        $errors = $exception->errors();
        foreach ($errors as $fieldErrors) {
            foreach ($fieldErrors as $error) {
                $this->error($error);
            }
        }
    }
}
