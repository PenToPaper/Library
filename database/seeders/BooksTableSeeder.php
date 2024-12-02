<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('books')->insert([
            [
                'title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'description' => 'A novel about racial injustice in the Deep South.',
                'cover_image' => 'https://m.media-amazon.com/images/I/81aY1lxk+9L._SL1500_.jpg',
                'publisher' => 'J.B. Lippincott & Co.',
                'publication_date' => '1960-07-11',
                'category' => 'Fiction',
                'isbn' => '9780060935467',
                'page_count' => 324,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'description' => 'A dystopian novel set in a totalitarian society.',
                'cover_image' => 'https://m.media-amazon.com/images/I/71rpa1-kyvL._SL1500_.jpg',
                'publisher' => 'Secker & Warburg',
                'publication_date' => '1949-06-08',
                'category' => 'Science Fiction',
                'isbn' => '9780451524935',
                'page_count' => 328,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'description' => 'A story about the Jazz Age and the American dream.',
                'cover_image' => 'https://m.media-amazon.com/images/I/61dRoDRubtL._SL1500_.jpg',
                'publisher' => 'Charles Scribner\'s Sons',
                'publication_date' => '1925-04-10',
                'category' => 'Fiction',
                'isbn' => '9780743273565',
                'page_count' => 180,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Moby Dick',
                'author' => 'Herman Melville',
                'description' => 'The narrative of Captain Ahab\'s obsessive quest to kill the white whale.',
                'cover_image' => 'https://m.media-amazon.com/images/I/71EPmLBheAL._SL1360_.jpg',
                'publisher' => 'Harper & Brothers',
                'publication_date' => '1851-10-18',
                'category' => 'Adventure',
                'isbn' => '9781503280786',
                'page_count' => 720,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Pride and Prejudice',
                'author' => 'Jane Austen',
                'description' => 'A classic romance novel about manners and marriage.',
                'cover_image' => 'https://m.media-amazon.com/images/I/81NLDvyAHrL._SL1500_.jpg',
                'publisher' => 'T. Egerton',
                'publication_date' => '1813-01-28',
                'category' => 'Romance',
                'isbn' => '9781503290563',
                'page_count' => 279,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'The Catcher in the Rye',
                'author' => 'J.D. Salinger',
                'description' => 'The story of a teenager navigating complex feelings of alienation.',
                'cover_image' => 'https://m.media-amazon.com/images/I/71nXPGovoTL._SL1500_.jpg',
                'publisher' => 'Little, Brown and Company',
                'publication_date' => '1951-07-16',
                'category' => 'Fiction',
                'isbn' => '9780316769488',
                'page_count' => 277,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'The Hobbit',
                'author' => 'J.R.R. Tolkien',
                'description' => 'A fantasy adventure about Bilbo Baggins and his quest to reclaim treasure.',
                'cover_image' => 'https://m.media-amazon.com/images/I/712cDO7d73L._SL1500_.jpg',
                'publisher' => 'George Allen & Unwin',
                'publication_date' => '1937-09-21',
                'category' => 'Fantasy',
                'isbn' => '9780547928227',
                'page_count' => 310,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Brave New World',
                'author' => 'Aldous Huxley',
                'description' => 'A dystopian novel about a society conditioned by technology and drugs.',
                'cover_image' => 'https://m.media-amazon.com/images/I/71GNqqXuN3L._SL1500_.jpg',
                'publisher' => 'Chatto & Windus',
                'publication_date' => '1932-08-18',
                'category' => 'Science Fiction',
                'isbn' => '9780060850524',
                'page_count' => 268,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Crime and Punishment',
                'author' => 'Fyodor Dostoevsky',
                'description' => 'The story of a man grappling with guilt after committing a crime.',
                'cover_image' => 'https://m.media-amazon.com/images/I/51Vg24nKbPL._SL1200_.jpg',
                'publisher' => 'The Russian Messenger',
                'publication_date' => '1866-01-01',
                'category' => 'Philosophical Fiction',
                'isbn' => '9780140449136',
                'page_count' => 671,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'War and Peace',
                'author' => 'Leo Tolstoy',
                'description' => 'An epic novel about Russian society during the Napoleonic era.',
                'cover_image' => 'https://m.media-amazon.com/images/I/81W6BFaJJWL._SL1500_.jpg',
                'publisher' => 'The Russian Messenger',
                'publication_date' => '1869-01-01',
                'category' => 'Historical Fiction',
                'isbn' => '9780199232765',
                'page_count' => 1225,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
