# WebDemo E-commerce Database Documentation

## Overview

This directory contains documentation for the WebDemo e-commerce application database schema. The documentation is designed to help developers understand the database structure, relationships, and data models used in the application.

## Documentation Files

### 1. DATABASE_SCHEMA.md

A comprehensive documentation of all database tables, their columns, and relationships. This file provides detailed information about:

- Table names and purposes
- Column names, types, and constraints
- Foreign key relationships
- Default values and nullable fields

Use this file when you need to understand the complete database structure in detail.

### 2. DATABASE_ERD.md

An Entity Relationship Diagram (ERD) in Mermaid format that visually represents the database schema. This diagram shows:

- All tables with their columns
- Relationships between tables
- Primary and foreign keys

View this file in a Markdown viewer that supports Mermaid diagrams to see the visual representation of the database structure.

### 3. schema.sql

A SQL file containing the complete database schema definition. This file can be used to:

- Create a new database with the same structure
- Reference the exact SQL definitions of tables
- Understand the database at the SQL level

## Database Structure

The WebDemo e-commerce application uses a relational database with the following main components:

1. **User Management**: Users, authentication, and profiles
2. **Product Catalog**: Categories, collections, and products
3. **Shopping**: Carts, wishlists, and checkout
4. **Order Processing**: Orders, order items, and payment transactions
5. **Content**: Reviews, inquiries, and sliders

## Migrations

The database schema is defined using Laravel migrations located in the `database/migrations` directory. These migrations are used to create and modify the database schema in a version-controlled way.

## Models

The corresponding Eloquent models for these database tables can be found in the `app/Models` directory.